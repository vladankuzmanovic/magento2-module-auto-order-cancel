# Auto Order Cancel
Mage2 extension to cancel orders automatically based on configuration

Configuration
------------------------
 
 STORES > Configuration > SALES > Sales > Auto Order Cancel 
 
        Enable - Yes/No
        Order Statuses to be Canceled - List of all existing order statuses in the web store. Chose order statuses to be canceled.
        Cancel Orders Older Than (minutes) - Value in minutes
        Cancel Orders Recent Than (minutes) - Value in minutes
        Order History Comment - Comment to be added after auto cancel

Technical feature
------------------------

        The auto cancel works on cron. 
        Cancellation is set up tp run each 5 minutes.
        Maximum order which can be canceled in one run is 10
        Cancellation starting from most recent order.


Installation
------------------------

Enter following commands to install module:

        ```bash
        cd MAGE2_ROOT_DIR
        # install
        composer config repositories.kuzman_autoordercancel git https://github.com/vladankuzmanovic/magento2-module-auto-order-cancel.git
        composer require kuzman/module-auto-order-cancel:dev-master
        # enable
        php bin/magento module:enable Kuzman_AutoOrderCancel --clear-static-content
        php bin/magento setup:upgrade
        php bin/magento setup:di:compile
        ```

Uninstall
------------------------

Enter following commands to disable and uninstall module:

        ```bash
        cd MAGE2_ROOT_DIR
        # disable
        php bin/magento module:disable Kuzman_AutoOrderCancel --clear-static-content    
        # uninstall
        php bin/magento module:uninstall Kuzman_AutoOrderCancel --clear-static-content
        ```

