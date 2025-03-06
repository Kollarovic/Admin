Admin
======

Admin
-----

![Alt text](https://raw.githubusercontent.com/Kollarovic/AdminDemo/master/www/images/admin.png "Admin")

Login
-----

![Alt text](https://raw.githubusercontent.com/Kollarovic/AdminDemo/master/www/images/login.png "Login")


Live Demo
=============

[Live Demo](http://demo.kollarovic.sk/admin)

[Github Demo](https://github.com/Kollarovic/AdminDemo)


Installation
=============

composer.json

```json
{
    "require":{
        "kollarovic/admin": "dev-master"
    }
}

```

config.neon

```yaml

extensions:
  navigation: Kollarovic\Navigation\DI\Extension
  admin: Kollarovic\Admin\DI\Extension
  
```

Admin
------

BasePresenter

```php

namespace App\BackendModule\Presenters;

use Nette\Application\UI\Presenter;
use Kollarovic\Admin\AdminControlFactory;


abstract class BasePresenter extends Presenter
{

    /** @var AdminControlFactory @inject */
    public $adminControlFactory;

    protected function createComponentAdminControl()
    {
        $adminControl = $this->adminControlFactory->create();
        return $adminControl;
    }

}

```

@layout.latte

```latte

{capture $content}
  {include content}
{/capture}

{control adminControl content=>$content}

```

Login
------

SignPresenter

```php

namespace App\BackendModule\Presenters;

use Kollarovic\Admin\LoginControlFactory;
use Nette\Application\UI\Presenter;


class SignPresenter extends Presenter
{

    /** @var  LoginControlFactory @inject */
    public $loginControlFactory;


    protected function createComponentLoginControl()
    {
        $loginControl = $this->loginControlFactory->create();
        $loginControl->onLoggedIn[] = function() {
            $this->redirect('Homepage:default');
        };
        return $loginControl;
    }

}

```

in.latte

```latte

{control loginControl}

```

