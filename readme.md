# Phalcon REST API Skeleton

This repo contains a basic rest application including:
- Simple user management
- Error handling
- Rest application
- Exceptions
- Authentication via JWT
- Logging
- Structure utilizing factory, repository and service pattern
- User specific configs

### User management

The user management provides a simple model having email, password and nickname. For account activation a token is set
on the entity during creation and will be unset when the user activated via mail which will be sent during registration.
To protect your controller actions from unauthorized access you have to add a annotation to the function php doc.

```php
/**
 * @authenticated(true)
 */
public function indexAction() {
  // Throws permission denied exception when no valid token is avilable
}

/**
 * @authenticated(false)
 */
public function indexAction() {
  // Throws permission denied exception when a valid token is avilable (requires to be logged out)
}
``` 