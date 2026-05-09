<?php
session_start();
require_once 'app/config.php';
require_once 'app/controllers/FrontController.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/AdminController.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home': FrontController::home(); break;
    case 'search': FrontController::search(); break;
    case 'reserve': FrontController::reserve(); break;
    case 'confirm_reserve': FrontController::confirmReserve(); break;
    case 'history': FrontController::history(); break;
    
    case 'login': AuthController::login(); break;
    case 'do_login': AuthController::doLogin(); break;
    case 'register': AuthController::register(); break;
    case 'do_register': AuthController::doRegister(); break;
    case 'logout': AuthController::logout(); break;
    
    case 'admin_dashboard': AdminController::dashboard(); break;
    case 'admin_cars': AdminController::cars(); break;
    case 'admin_car_form': AdminController::carForm(); break;
    case 'admin_car_save': AdminController::carSave(); break;
    case 'admin_car_delete': AdminController::carDelete(); break;
    
    case 'admin_reservations': AdminController::reservations(); break;
    case 'admin_res_update': AdminController::updateReservationStatus(); break;
    
    case 'admin_crm': AdminController::crm(); break;
    case 'admin_maintenance': AdminController::maintenance(); break;
    case 'admin_checkin': AdminController::checkin(); break;
    case 'admin_checkout_process': AdminController::checkoutProcess(); break;
    case 'admin_save_checkout': AdminController::saveCheckout(); break;
    case 'admin_print_contract': AdminController::printContract(); break;
    case 'admin_gantt': AdminController::gantt(); break;
    default: echo "404 Not Found"; break;
}