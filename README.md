
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
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade'); 
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
$event = new App\Models\Event(['title'=>'test event','owner_id'=>$user->id])

## TDD

test('authenticated users can visit the dashboard and see a list of all events and a list of events that they are an owner of', function () {
    $user = User::factory()->create();
    
    // Create events owned by the user
    $ownedEvents = Event::factory(3)->create(['user_id' => $user->id]);
    
    // Create events owned by other users
    $otherEvents = Event::factory(2)->create();
    
    $this->actingAs($user);
    
    $response = $this->get('/dashboard');
    
    $response->assertOk();
    
    // Assert all events are visible
    foreach ($ownedEvents->merge($otherEvents) as $event) {
        $response->assertSee($event->name);
    }
    
    // Assert owned events are marked/identified as owned by the user
    foreach ($ownedEvents as $event) {
        $response->assertSeeText($event->name); // You may want a more specific assertion here
    }
});


This test:

- Creates a user
- Creates 3 events owned by that user
- Creates 2 events owned by other users
- Verifies the dashboard shows all events
- Verifies the owned events are visible
- You may want to refine the "owned events" assertion based on how your dashboard markup identifies them (e.g., an "Edit" button, "owner" badge, etc.).

