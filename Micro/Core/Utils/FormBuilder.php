<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 17/02/2019
 * Time: 19:04
 */

namespace API\Core\Utils;


use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;

const CAPTCHA_INDEX = 6;  // remove field captcha if imagick is not installed

class FormBuilder
{
    private RenderInterface $renderer;
    private RouterInterface $router;

    /**
     * FormBuilder constructor.
     * @param RenderInterface $renderer
     * @param RouterInterface $router
     */
    public function __construct(RenderInterface $renderer, RouterInterface $router)
    {
        $this->renderer = $renderer;
        $this->router = $router;
    }
    public function buildLogIn()
    {
        $formData =  [
            'form' => [
            'action' => $this->router->generateURI('authUserService.authorizeUser', []),
            'method' => 'POST',
            'name' => 'logInForm',
            'id' => 'logInForm',
            'label' => 'Log In',
            'logo' => 'images/microphp.png'
            ],
            'fields' => [
            [
                'type' => 'email',
                'class' => 'form-control form-control-sm',
                'name' => 'email',
                'label' => 'Email',
                'labelClass' => '',
                'placeholder' => 'Tell us your mail',
            ],
            [
                'type' => 'password',
                'class' => 'form-control form-control-sm',
                'name' => 'pass',
                'label' => 'Password',
                'labelClass' => '',
                'placeholder' => 'Type your password',
            ],
            [
                'type' => 'checkbox',
                'class' => 'form-check-input',
                'name' => 'recover',
                'label' => 'Forgot my Password',
                'labelClass' => '',
                'onClick' => '',
            ],
//            [
//                'type' => 'checkbox',
//                'class' => 'form-check-input',
//                'name' => 'rme',
//                'label' => 'Remember me',
//                'labelClass' => 'form-check-label form-control-sm',
//                'placeholder' => '',
//                'onClick' => ''
//            ],
//            [
//                'type' => 'checkbox',
//                'class' => 'form-check-input',
//                'name' => 'authLog',
//                'label' => 'Keep me logged',
//                'labelClass' => 'form-check-label form-control-sm',
//                'placeholder' => '',
//                'onClick' => ''
//            ],
            [
                'type' => 'warning',
            ],
            ]
        ];


        return $this->renderer->template('formBuilder', $formData);
    }
    public function buildRegister($captcha)
    {

        $validAppLanguages = [
            'pt' => 'Portuguese',
            'en' => 'English',
            'fr' => 'French',
        ];
        $formData =
            [
                'form' => [
                    'action' => $this->router->generateURI('authUserService.store', []),
                    'method' => 'POST',
                    'name' => 'userRegister',
                    'id' => 'userRegister',
                    'label' => 'Register New User ',
                    'logo' => 'images/microphp.png'
                ],
                'fields' => [
                    [
                        'type' => 'text',
                        'class' => 'form-control form-control-sm',
                        'name' => 'name',
                        'label' => 'Name',
                        'labelClass' => '',
                        'placeholder' => 'Tell us your name',
                    ],
                    [
                        'type' => 'email',
                        'class' => 'form-control form-control-sm',
                        'name' => 'email',
                        'label' => 'Email',
                        'labelClass' => '',
                        'placeholder' => 'Tell us your mail',
                    ],
                    [
                        'type' => 'password',
                        'class' => 'form-control form-control-sm',
                        'name' => 'pass',
                        'label' => 'Password',
                        'labelClass' => '',
                        'placeholder' => 'Type your password',
                    ],
                    [
                        'type' => 'password',
                        'class' => 'form-control form-control-sm',
                        'name' => 'cPass',
                        'label' => 'Confirm Password',
                        'labelClass' => '',
                        'placeholder' => 'ReType your password',
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'name' => 'agree',
                        'label' => 'Agree with Terms',
                        'labelClass' => 'form-check-label form-control-sm',
                        'placeholder' => '',
                        'onClick' => ''
                        //'onClick' => 'this.form.submit()'
                    ],
                    [
                        'type' => 'select',
                        'selectItems' => $validAppLanguages,
                        'class' => 'form-control form-control-sm',
                        'id' => 'language',
                        'name' => 'language',
                        'label' => 'Preferred Language',
                        'labelClass' => '',
                        'placeholder' => '',
                    ],
                    [
                        'type' => 'captcha',
                        'image' => $captcha,
                        'label' => "Captcha",
                        'placeholder' => "Type the Word above, If you are human.",
                        'onClick' => 'window.resetCaptcha()'
                    ],
                    [
                        'type' => 'warning',
                    ],
                ]];

        return $this->renderer->template('formBuilder', $formData);
    }
    public function buildRecoverNoPass()
    {
        $formData =
            [
                'form' => [
                    'action' => $this->router->generateURI('authUserService.resetPassword', []),
                    'method' => 'POST',
                    'name' => 'passResetNoMail',
                    'id' => 'passResetNoMail',
                    'label' => 'Email to send the reset token',
                    'logo' => 'img/microphp.png'
                ],

                'fields' => [
                    [
                        'type' => 'email',
                        'class' => 'form-control form-control-sm',
                        'name' => 'email',
                        'label' => 'Email',
                        'labelClass' => '',
                        'placeholder' => 'Tell us your mail',
                    ],
                ]];
//        $foo= $this->template->template('formBuilder', $formData);
//        dump($foo);
//        die;
        return $this->renderer->template('formBuilder', $formData);
    }
    public function buildConfirmResetPass()
    {
        $formData =
            [
                'form' => [
                    'action' => $this->router->generateURI('authUserService.confirmNewPass', []),
                    'method' => 'POST',
                    'name' => 'confirmNewPass',
                    'id' => 'confirmNewPass',
                    'label' => 'Enter Code to Reset Password',
                    'logo' => 'images/microphp.png'
                ],

                'fields' => [
                    [
                        'type' => 'text',
                        'class' => 'form-control form-control-sm',
                        'name' => 'r_code',
                        'label' => 'Private Code',
                        'labelClass' => '',
                        'placeholder' => 'Insert your Private Code',
                    ],
                    [
                        'type' => 'password',
                        'class' => 'form-control form-control-sm',
                        'name' => 'pass',
                        'label' => 'New Password',
                        'labelClass' => '',
                        'placeholder' => 'Type a new Password',
                    ],
                    [
                        'type' => 'password',
                        'class' => 'form-control form-control-sm',
                        'name' => 'cPass',
                        'label' => 'Retype the password',
                        'labelClass' => '',
                        'placeholder' => 'Retype password',
                    ],
                ]];
        return $this->renderer->template('formBuilder', $formData);
    }
}
