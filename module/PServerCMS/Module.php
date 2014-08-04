<?php

namespace PServerCMS;

use PServerCMS\Keys\Entity;
use PServerCMS\Model\AuthStorage;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use PServerCMS\Helper;
use Zend\ServiceManager\AbstractPluginManager;

class Module {
	public function onBootstrap( MvcEvent $e ) {
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach( $eventManager );
	}

	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function getViewHelperConfig(){
		return array(
			'factories' => array(
				'sidebarWidget' => function(AbstractPluginManager $pluginManager){
					return new View\Helper\SideBarWidget($pluginManager->getServiceLocator());
				},
                'formWidget' => function(AbstractPluginManager $pluginManager){
                    return new View\Helper\FormWidget($pluginManager->getServiceLocator());
                }
			)
		);
	}

	/**
	 * Expected to return \Zend\ServiceManager\Config object or array to
	 * seed such an object.
	 *
	 * @return array|\Zend\ServiceManager\Config
	 */
	public function getServiceConfig() {
		return array(
			'invokables' => array(
				'pserver_user_service'				=> 'PServerCMS\Service\User',
				'pserver_mail_service'				=> 'PServerCMS\Service\Mail',
				'pserver_usercodes_service'			=> 'PServerCMS\Service\UserCodes',
				'pserver_configread_service'		=> 'PServerCMS\Service\ConfigRead',
				'pserver_ticketsystem_service'		=> 'PServerCMS\Service\TicketSystem',
			),
			'factories' => array(
				'user_auth_service' => function($sm){
					/** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
					/** @var \DoctrineModule\Authentication\Adapter\ObjectRepository $oAdapter */
					$oAdapter = $sm->get('doctrine.authenticationadapter.odm_default');

					// In Config there is not EntityManager =(, so we have to add it now =)
					/** @var \PServerCMS\Service\ConfigRead $oConfigService */
					$oConfigService = $sm->get('pserver_configread_service');
					$aConfig = $oConfigService->get('authenticationadapter.odm_default', array());
					$aConfig['objectManager'] = $sm->get('Doctrine\ORM\EntityManager');
					$oAdapter->setOptions( $aConfig );

					$oAuthService = new \Zend\Authentication\AuthenticationService();
					$oAuthService->setStorage( new AuthStorage() );
					return $oAuthService->setAdapter($oAdapter);
				},
				'pserver_user_register_form' => function($sm){
					/** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
					/** @var $oRepositoryUser \Doctrine\Common\Persistence\ObjectRepository */
					$oRepositoryUser = $sm->get('Doctrine\ORM\EntityManager')->getRepository(Entity::Users);
					$form = new Form\Register();
					$form->setInputFilter(new Form\RegisterFilter(
						new Validator\NoRecordExists( $oRepositoryUser, 'username' )
					));
					return $form;
				},
                'pserver_user_login_form' => function(){
                    $form = new Form\Login();
                    $form->setInputFilter(new Form\LoginFilter());
                    return $form;
                },
				'pserver_user_password_form' => function(){
					$form = new Form\Password();
					$form->setInputFilter(new Form\PasswordFilter());
					return $form;
				},
				'pserver_user_pwlost_form' => function($sm){
					/** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
					/** @var $oRepositoryUser \Doctrine\Common\Persistence\ObjectRepository */
					$oRepositoryUser = $sm->get('Doctrine\ORM\EntityManager')->getRepository(Entity::Users);
					$form = new Form\PwLost();
					$form->setInputFilter(new Form\PwLostFilter(
						new Validator\RecordExists( $oRepositoryUser, 'username' )
					));
					return $form;
				},
				'pserver_ticketsystem_new_form' => function($sm){
					/** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
					$form = new Form\TicketSystem($sm->get('Doctrine\ORM\EntityManager'));
					$form->setInputFilter(new Form\TicketSystemFilter());
					return $form;
				}
			),
		);
	}

}
