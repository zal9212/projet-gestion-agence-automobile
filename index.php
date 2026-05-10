<?php
/**
 * ROUTEUR VANILLA PHP
 */
session_start();
require_once 'app/config.php';
require_once 'app/controllers/front_controller.php';
require_once 'app/controllers/auth_controller.php';
require_once 'app/controllers/admin_controller.php';
require_once 'app/helpers.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    // ── FRONT ──
    case 'home':               front_home(); break;
    case 'search':             front_search(); break;
    case 'reserve':            front_reserve(); break;
    case 'confirm_reserve':    front_confirm_reserve(); break;
    case 'history':            front_history(); break;
    case 'cancel_reservation': front_cancel_reservation(); break;
    case 'profile':            front_profile(); break;
    case 'profile_save':       auth_profile_save(); break; // Géré par Auth pour tous

    // ── AUTH ──
    case 'login':       auth_login(); break;
    case 'do_login':    auth_do_login(); break;
    case 'register':    auth_register(); break;
    case 'do_register': auth_do_register(); break;
    case 'logout':      auth_logout(); break;

    // ── ADMIN ──
    case 'admin_dashboard':      admin_dashboard(); break;
    case 'admin_profile':        admin_profile(); break;
    case 'admin_cars':           admin_cars(); break;
    case 'admin_car_form':       admin_car_form(); break;
    case 'admin_car_save':       admin_car_save(); break;
    case 'admin_car_delete':     admin_car_delete(); break;
    case 'admin_reservations':   admin_reservations(); break;
    case 'admin_res_update':     admin_res_update(); break;
    case 'admin_crm':            admin_crm(); break;
    case 'admin_crm_blacklist':  admin_crm_blacklist(); break;
    case 'admin_crm_view':       admin_crm_view(); break;
    case 'admin_maintenance':    admin_maintenance(); break;
    case 'admin_checkin':        admin_checkin(); break;
    case 'admin_checkout_process': admin_checkout_process(); break;
    case 'admin_save_checkout':  admin_save_checkout(); break;
    case 'admin_print_contract': admin_print_contract(); break;
    case 'admin_gantt':          admin_gantt(); break;

    // ── NOTIFICATIONS ──
    case 'notif_read':           auth_notif_read(); break;

    default:
        echo "Page introuvable";
        break;
}