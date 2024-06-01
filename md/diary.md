2024-06-01

(FRONTEND)
AuthService.tsx:
 - Delete accessToken in authByToken();


(BACKEND)

Model.php 
    
    - searchBySingleEntity error handling rework
    - paginate error handling rework
  ------------------------------------------------------------


AdminController.php
    
    - login() error handling rework


Admin.php (Model)
  
    - loginAdmin()  exceptions added

 ------------------------------------------------------------
CapacityController.php  
   
    - index() error handling rework
  
 Capacity.php (MModel)
    
    - getDefaultCapacity()  exceptions added


 ------------------------------------------------------------

 ReservationController.php
    
    -index() error handling rework
    

  Reservation.php (Model)

    - getAllReservationsByMultipleQuery() exceptions added