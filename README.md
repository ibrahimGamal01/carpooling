# Carpooling

## Activity Diagram

```plantuml
@startuml ActivityDiagramCarpooling
start

:Login;
:Is user a driver or a rider?;
if (Driver) then (True)
  :Create a ride offering;
  :Set ride details (date, time, pickup location, etc.);
  :Publish the ride offering within the organization;
  :Wait for riders to join;
  :Are there any issues or conflicts?;;
  if (Yes) then (True)
    :Resolve the issue;
  endif
  :Start the ride;
  :End the ride;
  :Provide ride feedback;
  stop
else (Rider)
  :Search for available rides within the organization;
  :Select desired ride(s);
  :Reserve seat(s);
  :Are there any issues or conflicts?;;
  if (Yes) then (True)
    :Resolve the issue;
  endif
  :Join the ride;
  :Provide ride feedback;
  stop
endif

@enduml
