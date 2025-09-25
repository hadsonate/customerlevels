# vendor-customer-levels
## Vendor Customer Levels


**Vendor Customer Levels** introduces a new “Customer Levels” feature.
Customers are assigned a tier level based on the number of orders they have successfully
placed.
### Levels
#### Tier 1: 1 order
#### Tier 2: 5 orders
#### Tier 3: 10 orders
#### Tier 4: 50 orders
#### Tier 5: 100 orders
### Tier Calculation

1. **Tier Assignment**
   - #### 0 orders → Tier 0
   - #### 1–4 orders → Tier 1
   - #### 5–9 orders → Tier 2
   - #### 10–49 orders → Tier 3
   - #### 50–99 orders → Tier 4
   - #### 100+ orders → Tier 5

## Module Setup Instructions

### Initial Setup

1. Go to the Magento Admin Panel.
2. Navigate to:  
   **Stores > Configuration > Customers > Customer Levels > Tier Sets**
3. Configure the following:
    - **Tier Level** – Define the level of the customer (By Default it is set to 1-5).
    - **Tier Cap** – Set cap limit of order for a specific tier (Ex. Lvl 1: 4 max cap).
    - **Enabled/Disabled** – Enable/Disable this specific tier.

Save the configuration once done then clear cache.


### For Testing
#### Manually
1. Add a product to the cart and place an order.
2. Navigate to:  
   `Customers > All Customers > [Select a customer] > Account Information > Customer Order Count`
3. Verify that the **Customer Order Count (Customer Level)** attribute is displayed and correctly updated.  
4. Navigate to:  
   `Sales > Orders > [Select Invoiced Order] > Click Credit Memo > Refund`
5. Check Steps 2 - 3 To verify that **Customer Count** decreases.



#### Via Postman

#####  Authentication
1. Open Postman
2. Send a `POST` request to generate an **Admin Bearer Token**:
`{
   "username": "yourAdminUsername",
   "password": "yourAdminPassword"
}`
3. Use the response as our **Bearer Token** Authentication:
`POSTMAN > Authorization > Auth Type > Bearer Token : Token`
#####  Application
1. To Retrieve Tier Level using Customer ID:
   `GET`,
`/rest/default/V1/customerlevels/customers/:customerId/tierlevel`
   <br/>
Example:
`GET http://magento.local/rest/default/V1/customerlevels/customers/123/tierlevel`
2. To Retrieve Tier Level by Increment Value:
   `GET`,
   `/rest/default/V1/customerlevels/customers/:increment/tierlevelbyincrement`
   <br/>
   Example:
`GET http://magento.local/rest/default/V1/customerlevels/customers/123/tierlevelbyincrement`
3. To Add increment to a Customer Order Count:
   `POST`,
   `rest/V1/customerlevels/customers/increment`
   <br/>
   Example:
   `POST http://magento.local/rest/V1/customerlevels/customers/increment`
<br/>
`BODY > raw`
`{
   "customerId": 25628,
   "increment": 1
   }`

