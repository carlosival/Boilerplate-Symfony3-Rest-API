
A Boilerplate Symfony3 for Rest API development.

Key Bundles Installed and Configured Bundles

LexikJWTAuthenticationBundle


Generate the SSH keys :

$ mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
Configuration

Configure the SSH keys path in your config.yml :

lexik_jwt_authentication:
    private_key_path: '%jwt_private_key_path%'
    public_key_path:  '%jwt_public_key_path%'
    pass_phrase:      '%jwt_key_pass_phrase%'
    token_ttl:        '%jwt_token_ttl%'
Configure your parameters.yml.dist :

jwt_private_key_path: '%kernel.root_dir%/../var/jwt/private.pem' # ssh private key path
jwt_public_key_path:  '%kernel.root_dir%/../var/jwt/public.pem'  # ssh public key path
jwt_key_pass_phrase:  ''                                         # ssh key pass phrase
jwt_token_ttl:        3600
Configure your security.yml :

security:
    # ...
    
    firewalls:

        login:
            pattern:  ^/api/login
            stateless: true
            anonymous: true
            form_login:
                check_path:               /api/login_check
                success_handler:          lexik_jwt_authentication.handler.authentication_success
                failure_handler:          lexik_jwt_authentication.handler.authentication_failure
                require_previous_session: false

        api:
            pattern:   ^/api
            stateless: true
            guard:
                authenticators:
                    - lexik_jwt_authentication.jwt_token_authenticator

    access_control:
        - { path: ^/api/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/api,       roles: IS_AUTHENTICATED_FULLY }
Configure your routing.yml :

api_login_check:
    path: /api/login_check
