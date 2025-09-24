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

1. Add a product to the cart and place an order.
2. Navigate to:  
   **Customers > All Customers > [Select a customer] > Account Information > Customer Order Count**
3. Verify that the **Customer Order Count (Customer Level)** attribute is displayed and correctly updated.  