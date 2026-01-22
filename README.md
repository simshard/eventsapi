## SETUP ##
> ### Download or Clone this Repository
>
> ` https://github.com/simshard/eventsapi `
> 
> #### at a terminal ####    ` composer run dev `
> 
> set up database `php artisan migrate -seed`
> 
> App runs in browser at  [http://127.0.0.1:8000]
>

 ### Login to dashboard with ###
> ~~~
>           'email' => tester@example.com
>           'password' =>  password
> 
>           OR    create a new user
> ~~~



    

### Brief for Event Booking API 
 RESTful API for an Event Booking System that allows users to manage event bookings
while ensuring proper database design, request validation, and coding standards.

## Managing Events
- Users should be able to create, update, delete, and list events.
## Managing Attendees
- Users should be able to register attendees and manage their information.
## Booking System
- Users should be able to book an event.
- The system should prevent overbooking and duplicate bookings.
## Authentication & Authorization (Implementation not required, only mention how it would be structured)
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

## Bonus (Optional Enhancements)
-  Pagination and filtering for listing events.
-  API documentation (Swagger/Postman).
-  Docker support for easy deployment.
  
# Evaluation Criteria #
This task is designed to assess:

- Database schema design (relationships, constraints, and normalization).
- Application architecture (modularity, clean code, separation of concerns).
- Implementation of validation and error handling.
- Use of design patterns and best practices.
- Testing approach (expecting meaningful test coverage).

## ############### NOTES ############################## ##

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

SOLID Principles Assessment - Updated Review
Based on your current implementation with interfaces and repositories, here's the revised assessment:

✅ Single Responsibility Principle (SRP)

- Controllers - Handle HTTP requests only, delegate to services
- Services - Encapsulate business logic (booking validation, capacity checks, duplicate prevention)
- Repositories - Abstract database queries exclusively
- Policies - Handle authorization separately
- Requests - Validate incoming data only
- Example: BookingService focuses solely on booking logic, EventAvailabilityService handles capacity checks independently.

✅ Open/Closed Principle (OCP)
- Strong Implementation:
- Repository pattern allows swapping implementations without changing services/controllers
- Services depend on repository interfaces, not concrete classes
- New repository implementations can be created without modifying existing code
- Example: Can create CachedEventRepository without touching EventService or controllers.

✅ Liskov Substitution Principle (LSP)
- Strong Implementation:
- All EventRepository implementations honor the EventRepositoryInterface contract
- All BookingRepository implementations can substitute each other
- Services receive interfaces, not concrete types
- Current Status: Excellent - no violations expected.

✅ Interface Segregation Principle (ISP)
- Good Implementation:
- Separate interfaces for different concerns:
- EventRepositoryInterface vs BookingRepositoryInterface vs AttendeeRepositoryInterface
- EventServiceInterface vs BookingServiceInterface

- Consider splitting EventRepositoryInterface further:
- EventQueryRepositoryInterface (read operations: paginate, getUpcoming, getPast)
- EventCommandRepositoryInterface (write operations: create, update, delete)
- This follows CQRS principles and prevents clients from depending on methods they don't use.

✅ Dependency Inversion Principle (DIP)
- Excellent Implementation:
- Services depend on repository interfaces, not concrete classes
- AppServiceProvider binds interfaces to implementations
- Constructor injection in all services


Principle	Status	Notes
SRP	✅ Strong	Clear separation of concerns
OCP	✅ Strong	Repository pattern enables extension
LSP	✅ Strong	Interface contracts respected
ISP	⚠️ Good	Consider splitting query/command operations (not Done)
DIP	⚠️ Good	Fixed  constructors use interfaces rather than concrete classes

**Improvements:**

1. **Explicit Repository Interfaces**
   // Create interfaces for repositories
   This enforces DIP and makes swapping implementations easier.
2. **Service Contracts**
   // Define service interfaces
   interface BookingServiceInterface 
3. **Dependency Injection Container**
   Bind interfaces to implementations in `AppServiceProvider`:
4. **Separate Query & Command Services**
   - Create separate services for read operations (queries) vs write operations (commands)
   - Improves testability and adheres to CQRS principles
5. **Value Objects**
   - Consider creating value objects for booking validation rules, capacity checks
   - Makes business logic more reusable and testable

 
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
- Event belongs to a User
- Event has many Bookings
- Event has many Attendees
- Event title is required
- Event start_time is required
- Event end_time is required
- Event venue_capacity is required and must be positive
- Event start_time must be before end_time
- Event can calculate available capacity (venue_capacity - confirmed bookings count)
- Event scope: upcoming events (start_time > now)
- Event scope: past events (start_time <= now)
- Event scope: by user (filter by creator)
- Event scope: available events (available capacity > 0)
- Event is fully booked when confirmed bookings equal venue_capacity

