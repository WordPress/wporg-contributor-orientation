# Contritbutor Orientation Block

A block to help contributors learn where they'll fit best. 

## Getting started

**Prerequisites**

- Docker (see [wp-env prerequesites](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/#prerequisites))
- [Composer](https://getcomposer.org/)
- [Node](https://nodejs.org/en)
- [nvm](https://github.com/nvm-sh/nvm) (optional)

This uses wp-env to set up a dev enviroment, and wp-scripts to build the block assets.

1. Install the dependencies
    ```bash
    nvm use # if you use nvm.
    npm install
    composer install
    ```
2. Build the project
    ```bash
    npm run build
    ```
3. Start the local enviroment
    ```bash
    npm run wp-env start
    ```
4. Log in at [localhost:8888/wp-admin/](http://localhost:8888/wp-admin/), check that the "Contritbutor Orientation Block" plugin is active
5. Create a new page, and add the "Contritbutor Orientation Block" block

You should now be able to test the block on the page you've just created.

-------

## Example on make.WordPress.org

![contribute-step-1](https://github.com/ryelle/wporg-contributor-orientation/assets/541093/428c4637-d9a0-4575-9bad-584acec6726f)

![contribute-step-2](https://github.com/ryelle/wporg-contributor-orientation/assets/541093/13084aef-a0fc-4e6c-ba7a-784ad19a8203)

![contribute-step-3](https://github.com/ryelle/wporg-contributor-orientation/assets/541093/a4ec56a8-e4af-4632-9532-efd85e110f1c)

![contribute-step-4](https://github.com/ryelle/wporg-contributor-orientation/assets/541093/5a6b91ac-43c3-4671-a597-ad7e5c9020aa)
