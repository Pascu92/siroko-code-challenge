# Siroko Code Challenge


## Instalacion DB 

1. Crear DB
```bash
mysql -u <USUARIO> -p -e "CREATE DATABASE carrito_compras;"
```
2. En el .env modificar esta linea
```bash
DATABASE_URL="mysql://<USUARIO>:<CONTRASEÑA>@127.0.0.1:3306/carrito_compras"
```

## Ejecutar Migraciones
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## Probar Endpoints
1. Crear un carrito
   - Url: http://127.0.0.1:8000/api/cart
   - Method: POST
   
2. Añadir producto al carrito
    - Url: http://127.0.0.1:8000/api/cart/{cartId}/product
    - Method: POST
    - Body: ```{
      "name": "Product 1",
      "price": 50.0,
      "quantity": 2
      }```
   
3. Actulizar un producto
   - Url: http://127.0.0.1:8000/api/cart/{cartId}/product
   - Method: PATCH
   - Body: ```{
         "name": "Product 1",
         "price": 60,
         "quantity": 2
         }```

4. Eliminar un producto
   - Url: http://127.0.0.1:8000/api/cart/{cartId}/product/{productId}
   - Method: DELETE

5. Confirmar un carrito
    - Url: http://127.0.0.1:8000/api/cart/{cartId}/confirm
    - Method: POST

6. Obtener total de productos
    - Url: http://127.0.0.1:8000/api/cart/1/total-products
    - Method: GET

## Ejecutar Pruebas
```bash
php bin/phpunit
```
