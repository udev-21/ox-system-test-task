## Task
[Task link](https://telegra.ph/Ox-system-hiring-test-project-07-11)

database scheme: https://dbdiagram.io/d/6131f4d3825b5b0146f1c5e1
## route:list
```
POST      api/auth/login ........................................................ › AuthController@login
POST      api/auth/refresh .................................................... › AuthController@refresh
POST      api/auth/register .................................................. › AuthController@register
GET       api/auth/user-profile ........................................... › AuthController@userProfile
GET       api/categories .................................................... › CategoryController@index
POST      api/categories .................................................... › CategoryController@store
PUT       api/categories/{category} ........................................ › CategoryController@update
DELETE    api/categories/{category} ........................................ › CategoryController@delete
GET       api/categories/{category} ....................................... › CategoryController@details
GET       api/features ....................................................... › FeatureController@index
POST      api/features ....................................................... › FeatureController@store
PUT       api/features/{feature} ............................................ › FeatureController@update
DELETE    api/features/{feature} ............................................ › FeatureController@delete
GET       api/features/{feature} ........................................... › FeatureController@details
GET       api/products ....................................................... › ProductController@index
POST      api/products ....................................................... › ProductController@store
PUT       api/products/{product} ............................................ › ProductController@update
GET       api/products/{product} ........................................... › ProductController@details
DELETE    api/products/{product} ............................................ › ProductController@delete
```
#### Plase take a look at: routes/api.php
- Register/Login/Refresh jwt token 
- CRUD for categories
- CRUD for features
- CRUD for products

### Tables: 
- Features
- Categories
- CategoryFeatures
- Products
- ProductFeatures
- ProductFeatureValues

CategoryID required for create product

CategoryFeatures is required features for category (and also while create product you need pass all of the feature values for those features)

You can pass additional feature with value for product create
