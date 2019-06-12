# TravelAndExplore
Travel Search application with an attempt to create a chat bot with php and javacript

This is an improvement over Travel search project as the order part is extended to include payment processing with 
integration of Paypal and Stripe. The project is divided into 3 parts:
1. Search : user can search the database for flight, hotels and activities.
2. Order: user can order for a particular service or services based on the search results. Payment is processes either through Paypal or Stripe.
3. Virtual Assistant: Virtual assistance is chat bot with a knowledge base to help users in their search.

TECHNOLOGIES USED:
1. CodeIgniter Framework (PHP)
2. Javascript
3. MYSQL
4. HTML

OTHER IMFORMATION:
1. The database file is in the resources folder
2. It should be straight forward to setup but if you run into dificulties, you can contact me
3. Project can be developed and used for School projects or other purposes.

TEST CASES FOR VIRTUAL ASSISTANT:

FLIGHTS
1. One way flight from London to Amsterdam:
  Pre - Action : User clicks on Wanted Holiday
  i. I want flight from London to Amsterdam on 11/03/2018
  ii.Flights from London to Amsterdam on 11/03/2018
  Post -  Action : User clicks on Results button or type "result" to display suggestions

2. Two way/ Return Flight from London to Amsterdam
   Pre - Action : User clicks on Wanted Holiday
   i. I want flight from London to Amsterdam on 11/03/2018 - 13/03/2018
   ii.Flights from London to Amsterdam on 11/03/2018 - 13/03/2018
   Post -  Action : User clicks on Results button or type "result" to display suggestions

3. One way flight from Berlin to Hongkong
   Pre - Action : User clicks on Wanted Holiday
   i. I need to fly from Berlin to Hongkong on 15/03/2018
   ii. Cheap flights from Berlin to Hongkong on 15/03/2018 
   Post -  Action : User clicks on Results button or type "result" to display suggestions
4. One way flight from New York to London
   Pre - Action : User clicks on Wanted Holiday
   i. flying from New York to London on 29/03/2018
   ii. travelling from New York to London on 29/03/2018 
   Post -  Action : User clicks on Results button or type "result" to display suggestions


HOTELS:
1. Searching for Hotel rooms in Amsterdam:
  Pre - Action : User clicks on Wanted Holiday
  i. I need a hotel room in Amsterdam
  ii.cheap room in Amsterdam
  Post -  Action : User clicks on Results button or type "result" to display suggestions
2. Searching for Hotel rooms in London:
  Pre - Action : User clicks on Wanted Holiday
  i. hotel room offers in London
  ii.looking for hotel in London
  Post -  Action : User clicks on Results button or type "result" to display suggestions


ACTIVITIES:
1. Searching for Hotel rooms in Amsterdam:
  Pre - Action : User clicks on Wanted Holiday
  i. I want flight from London to Amsterdam on 11/03/2018 and hotel room in Amsterdam and Bruges Day activities in Amsterdam Europe in Spring for Young-adult
  ii.looking for Bruges Day activities in Amsterdam Europe
  Post -  Action : User clicks on Results button or type "result" to display suggestions
