                                              # Bolttech Barcelona Backend Test #

Welcome to Bolttech, the fastest growing insurtech in the world.

You are landing on a new business team, within a squad called Carental. This unit will develop a new platform for car rental in Barcelona.
The project is in design and discovery status, but we have been asked to create an MVP in order to prepare a demo for the global leads team in next weeks.

After long meetings, high level discussions and a lot of coffee, the product team decided that what is most important for this MVP is to show the bookings motor that we will prepare.

You will prepare the backend of this new project, you have a greenfield, there is no code, no restriction and no limits on your imagination; but as nothing comes free, you will have som minimal requisites to fulfill before the demo happens.

# User stories #
### US 1 ###
As a customer 
I want to see the availability of cars on concrete time slots
So I can be informed of pricing and stock
#### Requisites ####
* All available cars for the complete time slot will be returned.
* All cars returned will have the complete booking price, and a average day/price.


### US 2 ###
As a customer
I want to create a booking for a car
#### Requisites ####
* A user can have only one booking on the same dates.
* Driving license must be valid through all booking period.

# Our expectations #
* There is no limits here, but keep in mind that this is an MVP, not a commercial application.
* While this can take as many time as you want, we don't expect you to be working on this for 20 hours, if you feel you are not enjoying this, just write down in a .md file what you have left and how would you like to do it.
* We like to work with DDD, EDD, TDD and anything that has two "D" in its name.
* Tests should be written for at least one use case, we prefer you to finish one use case but tested than both cases without any test.
* We expect quality over quantity, SOLID, hexagonal and design patterns should be present, but don't take much more time, this is a test.
* A detailed README and documentation is needed to test and understand the app.

# How to publish this test #
* We will provide you a full week to do this test, take your time, enjoy it. If you feel you will need some more time, just tell us, we only want you to enjoy this exercise.
* The test will be a fork from the original repo and you'll get access to. Use Pull Requests and do small commits to master branch.
* If you are stuck, have any doubt or problem, don't be afraid, raise your hand and send an email to marcal.berga@bolttech.io
* Once the time has passed or you tell us that the work is finished, we will block the repository and a review meeting will be scheduled. If you deliver this exercise you will always have the review.

# Predefined values #
There are 3 main seasons:

* peak season - 1st of June to 15th of September
* mid season - 15th of September to 31st of October, 1st of March to 1st of June
* off-season - 1st of November to 1st of March

And the cars we will have in our warehouses:

| Brand | Model | Stock | Peak season price | Mid season price | Off-season price |
|---|---|---|---|---|---|
| Seat | León | 3 | 98,43 | 76,89 | 53,65 |
| Seat | Ibiza | 5 | 85,12 | 65,73 | 46,85 |
| Nissan | Qashqai | 2 | 101,46 | 82,94 | 59,87 |
| Jaguar | e-pace | 1 | 120,54 | 91,35 | 70,27 |
| Mercedes | Vito | 2 | 109,16 | 89,64 | 64,97 |


# UPDATE: Develop Tasks #
### Environment information ###
To mount the environment docker is needed. Although it is prepared for mac. For other operating systems it may not work properly.

There is a Makefile file.

The make (or make help) command returns information from all commands.

To start the environment, follow these steps:

1.- make build
2.- make laravel-prepare
3.- create the .env file with the database data
```
DB_CONNECTION=mysql
DB_HOST=bolttech-db
DB_PORT=3306
DB_DATABASE=BolttechDb
DB_USERNAME=user
DB_PASSWORD=password
```
4.- make install-components


### Branch information ###
There are two branches.

The first, called ```us1_see_availability_slots```, with the first user story, and ```us2_booking_car``` with the second.

The second branch extends from the first, so both tasks can be queried from this branch.

### Tasks information ###
Both tasks have been completed. But there has only been time to carry out the tests for the first one.

To launch all the tests, in case you want to use the terminal, you must follow these steps:

1.- ```make ssh``` (with which the container is accessed)

2.- ```php artisan test```

The task endpoints are as follows:

***US1***
[GET] ```http://localhost:350/api/car/{dateStart}/{dateEnd}/available```

dateStart and dateEnd have to be dates of the format ```Y-m-d```

***US2***
[POST] ```http://localhost:350/api/booking/car```

This endpoint must be supplied with a payload of the following type:
```
{
	"car_id": "01c7ae59-b023-3cd1-8b5c-3cfb4bcd6508",
	"start": "2022-11-22",
	"end": "2022-11-26"
}
```

General Purpose Enpoints:

***REGISTRO***
[POST] ```http://localhost:350/api/auth/register```

Payload
```
{
	"email": "prueba@delamor.com",
	"password": "123123123",
	"password_confirmation": "123123123"
}
```

***LOGIN***
[POST] ```http://localhost:350/api/auth/login```

Payload
```
{
	"email": "prueba@delamor.com",
	"password": "123123123"
}
```

This last endpoint returns the token, in case of correct login, to be able to access the endpoints of the tasks.