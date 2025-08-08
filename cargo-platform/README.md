### Cargo Platform (Symfony 6.4)

Backend: Symfony 6.4, Doctrine, Messenger, EasyAdmin, JWT (Lexik), VichUploader, LiipImagine, PayPal (Guzzle)
Frontend: Vue 3 + Vite (Symfony Vite bundle)

Prerequisites: PHP >= 8.1, Composer, Node 18+, npm/yarn

Setup:
- composer install
- Generate JWT keys:
  - mkdir -p config/jwt
  - openssl genrsa -out config/jwt/private.pem -aes256 4096
  - openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
  - set passphrase in .env as JWT_PASSPHRASE
- php bin/console doctrine:database:create --if-not-exists
- php bin/console doctrine:migrations:migrate -n
- npm install
- npm run dev

Auth:
- Register: POST /api/register { email, password, role: client|courier }
- Login (JWT): POST /api/login_check { email, password }

Courier verification:
- Upload passport (courier): POST /api/courier/passport (multipart with `passport`)
- Admin verify & delete passport: POST /api/admin/courier/{id}/verify
- Pending list: GET /api/admin/couriers/pending

Orders:
- Create: POST /api/orders { weightKg, fromAddress, toAddress, direction }

PayPal:
- Frontend uses Smart Buttons with PAYPAL_CLIENT_ID
- Capture: POST /api/paypal/capture { paypalOrderId, orderId }
- Refund: POST /api/paypal/refund { paypalCaptureId }

Translations:
- RU and DE under translations/. Configure default locale in config/services.yaml

Notes:
- Storage for passports is private under var/private_uploads/passports, not web-accessible.
- To switch to S3, configure Flysystem and Vich accordingly.