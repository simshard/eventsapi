
### Event Booking API 
 RESTful API for an Event Booking System that allows users to manage event bookings
while ensuring proper database design, request validation, and coding standards.

## Managing Events
- Users should be able to create, update, delete, and list events.
Managing Attendees
- Users should be able to register attendees and manage their information.
Booking System
- Users should be able to book an event.
- The system should prevent overbooking and duplicate bookings.
Authentication & Authorization (Implementation not required, only mention how it would be
structured)
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
           // $table->ownerId('user_id');    //foreign key  
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


Tinker  
syntax for creating model instances in Tinker
$event =\App\Models\Event::factory()->create()
$event->save()

