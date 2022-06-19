<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */

    public function up()
    {
        try {
            Schema::create('movies', function ($table) {
                $table->increments('id');
                $table->string('title');
                $table->string('runtime');
                $table->string('release_date');
                $table->timestamps();
            });

            Schema::create('cities', function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
            });

            Schema::create('shows', function ($table) {
                $table->increments('id');
                $table->date('date');
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->foreignId('city_id')->references('id')->on('cities');  
                $table->foreignId('movie_id')->references('id')->on('movies');
                $table->timestamps();
            });
            
            Schema::create('cinema', function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->string('total_seat');
                $table->timestamps();
            });

            Schema::create('bookings', function ($table) {
                $table->increments('id');
                $table->integer('number_of_sheets');
                $table->foreignId('user_id')->references('id')->on('users');
                $table->foreignId('show_id')->references('id')->on('shows');
                $table->integer('status');
                $table->timestamps();
            });


            Schema::create('cinema_seat', function ($table) {
                $table->increments('id');
                $table->string('seat_number');
                $table->string('type');
                $table->foreignId('cinema_id')->references('id')->on('cinema');
                $table->timestamps();
            });
            Schema::create('show_seat', function ($table) {
                $table->increments('id');
                $table->string('status');
                $table->string('price');
                $table->foreignId('cinema_seat_id')->references('id')->on('cinema_seat');
                $table->foreignId('show_id')->references('id')->on('shows');
                $table->foreignId('booking_id')->references('id')->on('bookings');
                $table->timestamps();
            });
            Schema::create('payment', function ($table) {
                $table->increments('id');
                $table->foreignId('booking_id')->references('id')->on('bookings');
                $table->string('amount');
                $table->string('discount_coupon_id');
                $table->string('transaction_id');
                $table->string('payment_method');
                $table->timestamps();
            });
            
        } catch (\Throwable $th) {
            throw ($th);
            // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
