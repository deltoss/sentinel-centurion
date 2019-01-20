<?php

return array (
  'account' => [
    'already_exists' => 'Account with the credentials \':value\' exists.',
    'already_activated' => 'Account was already activated.',
    'deactivated' => 'Account has been deactivated. Please contact the administrators to reactivate your account.',
    'unactivated' => 'Please activate your account before trying to log in.',
    'needs_password_change' => 'Account password must be changed before the account can be activated.',
    'activation_failed' => 'Activation link has expired or is invalid.',
    'user_activation_failed' => 'Failed to activate \':email\'',
    'password_reset_failed' => 'Password reset link is invalid.',
    'invalid_credentials' => 'The provided credentials was not valid.',
    'locked' => 'Account has been locked after too many attempts, please try again later.',
    'incorrect_old_password' => 'Old password was not correct',
  ],
  'statements' => [
    'validation_error' => 'There were some problems with your input.',
    'requested_action_error' => 'Failed to perform the requested action.',
  ],
  'permissions' => [
    'assigned_roles' => 'There are roles that has this permission assigned.',
    'assigned_users' => 'There are users that has this permission assigned.',
    'slug_exists' => 'A permission with the slug of \':slug\' already exists.',
    'name_exists' => 'A permission with the name of \':name\' already exists.',
  ],
  'roles' => [
    'assigned_users' => 'There are users that has this role assigned.',
    'slug_exists' => 'A role with the slug of \':slug\' already exists.',
    'name_exists' => 'A role with the name of \':name\' already exists.',
  ],
  'captcha_required' => 'The captcha is required and can\'t be empty',
  'captcha_invalid' => 'The captcha is not valid',

  /*
  |--------------------------------------------------------------------------
  | Custom Validation Language Lines
  |--------------------------------------------------------------------------
  |
  | Here you may specify custom validation messages for attributes using the
  | convention "attribute.rule" to name the lines. This makes it quick to
  | specify a specific custom language line for a given attribute rule.
  |
  */
  'custom' => [
    'attribute-name' => [
        'rule-name' => 'custom-message',
    ],
  ],
  /*
  |--------------------------------------------------------------------------
  | Custom Validation Attributes
  |--------------------------------------------------------------------------
  |
  | The following language lines are used to swap attribute place-holders
  | with something more reader friendly such as E-Mail Address instead
  | of "email". This simply helps us make messages a little cleaner.
  |
  */
  'attributes' => [
    'email' => 'e-mail',
    'name' => 'name',
    'slug' => 'slug',
    'first_name' => 'first name',
    'last_name' => 'last name',
    'password' => 'password',
    'new_password' => 'new password',
    'old_password' => 'old password',
    'add_as' => 'add as',
  ],
);