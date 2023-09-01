## About Meeting Room Application (Front-end)
Booking Application - Vue CLI (Vuetify) completely separate application from Backend application. Performing the tasks (in API Consumption):

- User complete their registration and login.
- In dashboard user can search rooms available between two dates.
- If the room is booked, marked as room booked and can perform booking for available slots.
- In my-bookings section user can see the booking status: Approved, Pending, or Canceled.
- If user want to cancel the booking, need to contact admin support.

### Front-end Demo:
- Website Link: [https://booking.rashedulhasan.com](https://booking.rashedulhasan.com).
- User: john@example.com
- Password: 12345678




## About Meeting Room Application (Back-end)

- Admin can manage the available booking requests e.g. Booking approve, or cancel.
- In dashboard admin also can book a room on behalf of an user.
- During creating a booking: checking the available rooms and from the available rooms admin select a user (the room will be booked for him/her) and perform book action..

### Back-end Demo:
- Website Link: [https://bookingb.rashedulhasan.com](https://bookingb.rashedulhasan.com).
- Admin: admin@admin.com
- Password: 12345678


## Installation

In localhost:

### Backend
Download the backend project and go to the project direcotry to run these command:
```
composer update
```

if needed:
```
php artisan key:generate
```

```
composer dump-autoload
```

Change .env file according the database credentials -
```
php artisan migrate:fresh --seed
```

```
php artisan serve
```
Visit - 127.0.0.1:8000


### Frontend
Download the frontend project - [https://github.com/rashed50702/booking_front](https://github.com/rashed50702/booking_front) and Follow the steps.