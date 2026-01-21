
### Event Booking API 
 RESTful API for an Event Booking System that allows users to manage event bookings
while ensuring proper database design, request validation, and coding standards.

## Managing Events
- Users should be able to create, update, delete, and list events.
# Managing Attendees
- Users should be able to register attendees and manage their information.
# Booking System
- Users should be able to book an event.
- The system should prevent overbooking and duplicate bookings.
# Authentication & Authorization (Implementation not required, only mention how it would be structured)
- Assume that API consumers must be authenticated to manage events.
- Attendees should be able to register without authentication.

## Technical Expectations

- Database Schema Design: Define a relational schema to support the requirements.
- Data Validation: Ensure incoming requests are properly validated.
- Serialization & Normalization: Structure API responses in a clean and maintainable way.
- Design Patterns & Best Practices: Apply appropriate architectural patterns.
- Error Handling: Implement proper error responses for invalid requests.
- Testing: Provide unit and integration tests.
  
**Documentation**
 Include a README.md with setup instructions.
## Guidelines ##

- The API must support event bookings, and the database should be structured accordingly.
- Locations should be country-based rather than specific addresses.
- Users should not be able to book the same event multiple times.
- Consider capacity limitations when booking an event.
- The API should return structured, meaningful responses.
- The completed solution must be stored in a public repository (e.g., GitHub, GitLab, or Bitbucket) or
a private repository with an invitation provided to access it.

#Bonus# (Optional Enhancements)
-  Pagination and filtering for listing events.
-  API documentation (Swagger/Postman).
-  Docker support for easy deployment.
-  
# Evaluation Criteria #
This task is designed to assess:

- Database schema design (relationships, constraints, and normalization).
- Application architecture (modularity, clean code, separation of concerns).
- Implementation of validation and error handling.
- Use of design patterns and best practices.
- Testing approach (expecting meaningful test coverage).























# Build NOTES #

laravel new events api
livewire starter kit to include  user auth scaffolding and  Blade templates and Flux UI components

 but no rect/vue/inertia   - mainly this  is annoying extra shit that I would rather not deal with
 testing with pest -make a readme

install api routes     *php artisan install:api*  
 api test route     /api/demo

### plan for course schema 

 Schema::create('events', function (Blueprint $table) {  
            $table->id();  
            $table->string('title');  
            $table->text('description')->nullable();  
            $table->string('event_type')->nullable();  
            $table->string('location')->nullable();  
            $table->string('venue_name')->nullable();  
            $table->decimal('fee')->nullable();  
            $table->string('currency')->nullable();  
            $table->integer('venue_capacity');  
            $table->dateTime('start_time');  
            $table->dateTime('end_time')->nullable();  
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->timestamps();  
            });  

**php artisan make:model Event -a**

model,migration factory seeder   
request classes for store and update 
Resource class   controller policy classes

##To do list for TDD##

**logged in user**
- can see dashboard  and 
-  All events  and  users own events lists

- user can create ,  edit  , delete  an event
- user can book an event 
- there are a limited number of users can book  a specific event as venues have a max capacity
- user cannot book same event more than once 
- user cannot book a fully subscribed event


# Tinker  
syntax for creating model instances in Tinker
 $event = \App\Models\Event::factory()->make() temp
 $event = \App\Models\Event::factory()->create() persists  OR $event->save()
   
$user=User::factory()->create() 
User::factory()->has(\App\Models\Event::factory()->count(2))->make()

override vals??
$user= new App\Models\User( ['name' => 'tester','email'=>'tester@ytest.com', 'password' => bcrypt('password')])
$event = new App\Models\Event(['title'=>'test event','user_id'=>$user->id])

## Architure

## Architecture

This application follows a **layered architecture** using the **Repository Pattern** and **Service Layer**:

**Layer Responsibilities:**

- **Controllers** - Handle HTTP requests/responses and routing
- **Services** - Encapsulate business logic (booking validation, capacity checks, duplicate prevention)
- **Repositories** - Abstract database queries and data persistence
- **Policies** - Handle authorization (who can edit/delete resources)
- **Requests** - Validate incoming data

**Benefits:**

- **Separation of Concerns** - Each layer has a single responsibility
- **Testability** - Services and repositories can be unit tested independently
- **Maintainability** - Business logic is centralized in services, not scattered in controllers
- **Reusability** - Services can be called from multiple controllers or API endpoints

**Directory Structure:**

```
app/
├── Models/              # Eloquent models
├── Http/
│   ├── Controllers/     # Request handlers
│   └── Requests/        # Form request validation
├── Services/            # Business logic layer
├── Repositories/        # Data access layer
└── Policies/            # Authorization
```

## SOLID Principles Assessment

**Strengths:**

✅ **Single Responsibility Principle (SRP)**
- Services handle business logic (booking validation, capacity checks)
- Repositories handle data access only
- Controllers delegate to services, don't contain logic
- Policies handle authorization separately

✅ **Open/Closed Principle (OCP)**
- Repository pattern allows swapping implementations without changing controllers
- Services can be extended without modification

✅ **Liskov Substitution Principle (LSP)**
- Repositories can be swapped (interface-based)
- Services follow consistent contracts

✅ **Interface Segregation Principle (ISP)**
- Separate request validation classes per action
- Policies focused on specific authorization concerns

✅ **Dependency Inversion Principle (DIP)**
- Services inject repositories, not directly using models
- Controllers depend on service contracts, not implementations

**Recommended Improvements:**

1. **Explicit Repository Interfaces**
   ```php
   // Create interfaces for repositories
   interface EventRepositoryInterface {
       public function findById($id);
       public function create(array $data);
       public function update($id, array $data);
   }
   ```
   This enforces DIP and makes swapping implementations easier.

2. **Service Contracts**
   ```php
   // Define service interfaces
   interface BookingServiceInterface {
       public function book(User $user, Event $event): Booking;
       public function validateBooking(User $user, Event $event): bool;
   }
   ```

3. **Dependency Injection Container**
   Bind interfaces to implementations in `AppServiceProvider`:
   ```php
   $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
   $this->app->bind(BookingServiceInterface::class, BookingService::class);
   ```

4. **Separate Query & Command Services**
   - Create separate services for read operations (queries) vs write operations (commands)
   - Improves testability and adheres to CQRS principles

5. **Value Objects**
   - Consider creating value objects for booking validation rules, capacity checks
   - Makes business logic more reusable and testable

**Current Architecture Grade: B+**

Your code follows SOLID well. Adding explicit interfaces would elevate it to A and improve maintainability significantly.
 
######################################################################

### Booking Feature Tests
- User can book an available event
- User can view their bookings on dashboard
- User cannot book a fully booked event
- User cannot book the same event twice 
- Booking is rejected if event capacity is reached
- User can cancel a booking


### Attendee Tests
- Attendee name is required
- User can view all attendees for their event
- Event organizer can see booking details (who booked, when)
- Attendee list shows booking status (confirmed/cancelled)
- Attendee can be marked as cancelled
- Event with capacity of 5 accepts only 5 bookings and a booking is rejected when capacity     reached
- Cancelling a booking reduces attendee count



## Unit Tests
Event belongs to a User
Event has many Bookings
Event has many Attendees
Event title is required
Event start_time is required
Event end_time is required
Event venue_capacity is required and must be positive
Event start_time must be before end_time
Event can calculate available capacity (venue_capacity - confirmed bookings count)
Event scope: upcoming events (start_time > now)
Event scope: past events (start_time <= now)
Event scope: by user (filter by creator)
Event scope: available events (available capacity > 0)
Event is fully booked when confirmed bookings equal venue_capacity

