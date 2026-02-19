<?php

class CRC_Master{

  protected $agregartabs;

  public function __construct(){

    $this->cargar_dependencias();
    $this->cargar_instancias();

  }

  private function cargar_dependencias(){
    require_once CRC_DIR_PATH . 'includes/class-crc-redirect.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-users.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-userdashboard.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-admindashboard.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-agregarestilos.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-formularios.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-agregartemplates.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-metaboxes.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-funcionesajax.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-funcionesajaxagre.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-funcionesajaxcons.php';
    require_once CRC_DIR_PATH . 'includes/class-crc-databases.php';

  }

  private function cargar_instancias(){
    $this->redirect  = new CRC_Redirect();
    $this->users  = new CRC_Users();
    $this->usersdashboard  = new CRC_UserDashboard();
    $this->admindashboard  = new CRC_AdminDashboard();
    $this->agregarestilos  = new CRC_Agregarestilos();
    $this->formularios  = new CRC_Formularios();
    $this->agregartemplates  = new CRC_Agregartemplates();
    $this->metaboxes  = new CRC_Metaboxes();
    $this->funcionesajax  = new CRC_Funcionesajax();
    $this->funcionesajaxagre  = new CRC_FuncionesajaxAgre();
    $this->funcionesajaxcons  = new CRC_FuncionesajaxCons();
    $this->nuevatabla  = new CRC_Nuevatabla();
  }

  private function definir_admin_hooks(){
    add_action( 'login_footer', [ $this->redirect, 'add_link_register'] );
    add_action( 'template_redirect', [ $this->redirect, 'crc_redirect_home' ] );
    add_action( 'admin_bar_menu',  [ $this->redirect, 'add_link_to_admin_bar' ], 999);
    add_filter( 'login_redirect', [ $this->redirect, 'my_login_redirect'], 10, 3 );
    add_filter( 'wp_authenticate_user', [ $this->redirect, 'isUserActivated'] );
    add_filter( 'login_message', [ $this->redirect, 'add_inactive_userbox'] );
    add_filter( 'admin_title', [ $this->redirect, 'my_admin_title'], 10, 2 );
    add_action( 'init', [ $this->users, 'crc_rol_inversor' ] );
    add_action( 'init', [ $this->users, 'crc_rol_biglevel' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_menuinversionistas' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_menuverdepmes' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_menuverretmes' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_menuhistoretiros' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_menuhistodepositos' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_agremenuinversionistas' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_agremenuhistodepositos' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_agremenuhistoretiros' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_consmenuinversionistas' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_consmenuhistodepositos' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_consmenuhistoretiros' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_consverdepmes' ] );
    add_action( 'admin_menu', [ $this->usersdashboard, 'agregar_consverretmes' ] );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadmininversiones' ], 20 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminretiros' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadmindepositos' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminuserdashboard' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminverdepmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminverretmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminconmaster' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadmindepmaster' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminretmaster' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminverdepmasmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminverretmasmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminverutilrefmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_reportemensual' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefreportemes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefreportemesspe' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefusersnormal' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefusersspecial' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefvercuenta' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminreftotalcuentas' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefvercuentaspe' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminreftotalcuentasspe' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefdetallemes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefdetallemesspe' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefnftprojects' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefverdetallemesvar' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminrefnftverdetallemes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminagrdepositos' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminagrretiros' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuagrdepmaster' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuagrretmaster' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminverdepagrmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminverretagrmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminagrverinvestorsmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminagrlistauser' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminagruserdashboard' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadmincondepositos' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminconretiros' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menucondepmaster' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuconretmaster' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminconlistauser' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_menuadminconuserdashboard' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_adminconsverdepmes' ], 21 );
    add_action( 'admin_menu', [ $this->admindashboard, 'agregar_adminconsverretmes' ], 21 );
    add_action( 'admin_enqueue_scripts', [ $this->agregarestilos, 'agregarscripts_admin' ] );
    add_action( 'login_enqueue_scripts', [ $this->agregarestilos, 'agregarscripts_login' ] );
    add_action( 'show_user_profile', [ $this->metaboxes, 'campos_inversionista' ] );
    add_action( 'edit_user_profile', [ $this->metaboxes, 'campos_admininversionista' ] );
    add_action( 'admin_notices', [ $this->metaboxes, 'add_diveditmes' ], 20 );
    add_action( 'personal_options_update', [ $this->metaboxes, 'save_meta_fields' ] );
    add_action( 'edit_user_profile_update', [ $this->metaboxes, 'save_adminmeta_fields' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabretiros' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabdepositos' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabmesesinv' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabcontrolmaster' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabdepmaster' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabretmaster' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabreghistorico' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabprojectsbl' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabusuariosbl' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabcuentasbl' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabregistrosbl' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_nftprojects' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabregistrosnft' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabretirosnft' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabregistrosvar' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabagredepositos' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabagreretiros' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabagredepmaster' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabagreretmaster' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabagreregistros' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabconsdepositos' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabconsretiros' ] );
    add_action( 'after_setup_theme', [ $this->nuevatabla, 'crc_database_tabconsnuevosstatus' ] );
    add_action( 'wp_ajax_solicitar_retiro',[ $this->funcionesajax, 'solicitar_retiro']);
    add_action( 'wp_ajax_operacion_finalizar',[ $this->funcionesajax, 'operacion_finalizar']);
    add_action( 'wp_ajax_operacion_editar',[ $this->funcionesajax, 'operacion_editar']);
    add_action( 'wp_ajax_operacion_cancelar',[ $this->funcionesajax, 'operacion_cancelar']);
    add_action( 'wp_ajax_operacion_editmes',[ $this->funcionesajax, 'operacion_editmes']);
    add_action( 'wp_ajax_operacion_agregarmes',[ $this->funcionesajax, 'operacion_agregarmes']);
    add_action( 'wp_ajax_operacion_newbalance',[ $this->funcionesajax, 'operacion_newbalance']);
    add_action( 'wp_ajax_operacion_editbalance',[ $this->funcionesajax, 'operacion_editbalance']);
    add_action( 'wp_ajax_operacion_editreghist',[ $this->funcionesajax, 'operacion_editreghist']);
    add_action( 'wp_ajax_operacion_elimbal',[ $this->funcionesajax, 'operacion_elimbal']);
    add_action( 'wp_ajax_crear_opemaster',[ $this->funcionesajax, 'crear_opemaster']);
    add_action( 'wp_ajax_editar_opemaster',[ $this->funcionesajax, 'editar_opemaster']);
    add_action( 'wp_ajax_cancelar_opemaster',[ $this->funcionesajax, 'cancelar_opemaster']);
    add_action( 'wp_ajax_traer_datos',[ $this->funcionesajax, 'traer_datos']);
    add_action( 'wp_ajax_traer_datosbal',[ $this->funcionesajax, 'traer_datosbal']);
    add_action( 'wp_ajax_traer_datos_opemaster',[ $this->funcionesajax, 'traer_datos_opemaster']);
    add_action( 'wp_ajax_traer_datos_referraluser',[ $this->funcionesajax, 'traer_datos_referraluser']);
    add_action( 'wp_ajax_traer_datos_referralregistro',[ $this->funcionesajax, 'traer_datos_referralregistro']);
    add_action( 'wp_ajax_traer_datos_referralregistronft',[ $this->funcionesajax, 'traer_datos_referralregistronft']);
    add_action( 'wp_ajax_traer_datos_referralretironft',[ $this->funcionesajax, 'traer_datos_referralretironft']);
    add_action( 'wp_ajax_traer_datos_referralregistrovar',[ $this->funcionesajax, 'traer_datos_referralregistrovar']);
    add_action( 'wp_ajax_solicitar_deposito',[ $this->funcionesajax, 'solicitar_deposito']);
    add_action( 'wp_ajax_referral_registraruser',[ $this->funcionesajax, 'referral_registraruser']);
    add_action( 'wp_ajax_referral_editaruser',[ $this->funcionesajax, 'referral_editaruser']);
    add_action( 'wp_ajax_referral_agregarcuenta',[ $this->funcionesajax, 'referral_agregarcuenta']);
    add_action( 'wp_ajax_referral_editarcuenta',[ $this->funcionesajax, 'referral_editarcuenta']);
    add_action( 'wp_ajax_referral_editarproyectonft',[ $this->funcionesajax, 'referral_editarproyectonft']);
    add_action( 'wp_ajax_referral_editarproyectobl',[ $this->funcionesajax, 'referral_editarproyectobl']);
    add_action( 'wp_ajax_referral_agregarregistro',[ $this->funcionesajax, 'referral_agregarregistro']);
    add_action( 'wp_ajax_referral_agregarregistrospe',[ $this->funcionesajax, 'referral_agregarregistrospe']);
    add_action( 'wp_ajax_referral_editarregistro',[ $this->funcionesajax, 'referral_editarregistro']);
    add_action( 'wp_ajax_referral_editarregistronft',[ $this->funcionesajax, 'referral_editarregistronft']);
    add_action( 'wp_ajax_referral_editarretironft',[ $this->funcionesajax, 'referral_editarretironft']);
    add_action( 'wp_ajax_referral_editarregistrovar',[ $this->funcionesajax, 'referral_editarregistrovar']);
    add_action( 'wp_ajax_referral_borrarregistro',[ $this->funcionesajax, 'referral_borrarregistro']);
    add_action( 'wp_ajax_referral_borrarregistronft',[ $this->funcionesajax, 'referral_borrarregistronft']);
    add_action( 'wp_ajax_referral_borrarregistrovar',[ $this->funcionesajax, 'referral_borrarregistrovar']);
    add_action( 'wp_ajax_referral_borrarretironft',[ $this->funcionesajax, 'referral_borrarretironft']);
    add_action( 'wp_ajax_referral_borrarcuenta',[ $this->funcionesajax, 'referral_borrarcuenta']);
    add_action( 'wp_ajax_referral_borrarusuariobl',[ $this->funcionesajax, 'referral_borrarusuariobl']);
    add_action( 'wp_ajax_referral_borrarnftproject',[ $this->funcionesajax, 'referral_borrarnftproject']);
    add_action( 'wp_ajax_referral_borrarblproject',[ $this->funcionesajax, 'referral_borrarblproject']);
    add_action( 'wp_ajax_referral_agregarnftproject',[ $this->funcionesajax, 'referral_agregarnftproject']);
    add_action( 'wp_ajax_referral_agregarblproject',[ $this->funcionesajax, 'referral_agregarblproject']);
    add_action( 'wp_ajax_referral_agregarregistronft',[ $this->funcionesajax, 'referral_agregarregistronft']);
    add_action( 'wp_ajax_referral_agregarretironft',[ $this->funcionesajax, 'referral_agregarretironft']);
    add_action( 'wp_ajax_referral_agregarregistrovar',[ $this->funcionesajax, 'referral_agregarregistrovar']);
    add_action( 'wp_ajax_mostrarTablaHistoRet', [ $this->funcionesajax, 'mostrarTablaHistoRet'] );
    add_action( 'wp_ajax_mostrarTablaHistoDep', [ $this->funcionesajax, 'mostrarTablaHistoDep'] );
    add_action( 'wp_ajax_mostrarTablaAdminUsers', [ $this->funcionesajax, 'mostrarTablaAdminUsers'] );
    add_action( 'wp_ajax_mostrarTablaAdminRet', [ $this->funcionesajax, 'mostrarTablaAdminRet'] );
    add_action( 'wp_ajax_mostrarTablaAdminDep', [ $this->funcionesajax, 'mostrarTablaAdminDep'] );
    add_action( 'wp_ajax_mostrarTablaAdmConMaster', [ $this->funcionesajax, 'mostrarTablaAdmConMaster'] );
    add_action( 'wp_ajax_mostrarTablaRepHistGral', [ $this->funcionesajax, 'mostrarTablaRepHistGral'] );
    add_action( 'wp_ajax_mostrarTablaRepMensual', [ $this->funcionesajax, 'mostrarTablaRepMensual'] );
    add_action( 'wp_ajax_mostrarTablaAdminRetMas', [ $this->funcionesajax, 'mostrarTablaAdminRetMas'] );
    add_action( 'wp_ajax_mostrarTablaAdminDepMas', [ $this->funcionesajax, 'mostrarTablaAdminDepMas'] );
    add_action( 'wp_ajax_mostrarTablaReferralUsers', [ $this->funcionesajax, 'mostrarTablaReferralUsers'] );
    add_action( 'wp_ajax_mostrarTablaReferralUsersSpecial', [ $this->funcionesajax, 'mostrarTablaReferralUsersSpecial'] );
    add_action( 'wp_ajax_mostrarTablaReferralCuentaN', [ $this->funcionesajax, 'mostrarTablaReferralCuentaN'] );
    add_action( 'wp_ajax_mostrarTablaReferralCuentaS', [ $this->funcionesajax, 'mostrarTablaReferralCuentaS'] );
    add_action( 'wp_ajax_mostrarTablaReferralProyectoNFT', [ $this->funcionesajax, 'mostrarTablaReferralProyectoNFT'] );
    add_action( 'wp_ajax_mostrarTablaReferralProyectoNFTM', [ $this->funcionesajax, 'mostrarTablaReferralProyectoNFTM'] );
    add_action( 'wp_ajax_mostrarTablaReferralRetirosNFT', [ $this->funcionesajax, 'mostrarTablaReferralRetirosNFT'] );
    add_action( 'wp_ajax_mostrarTablaReferralProyectoNFTMes', [ $this->funcionesajax, 'mostrarTablaReferralProyectoNFTMes'] );
    add_action( 'wp_ajax_mostrarTablaReferralTotalCuentasN', [ $this->funcionesajax, 'mostrarTablaReferralTotalCuentasN'] );
    add_action( 'wp_ajax_mostrarTablaReferralTotalCuentasS', [ $this->funcionesajax, 'mostrarTablaReferralTotalCuentasS']);
    add_action( 'wp_ajax_mostrarTablaReferralDetalleMesN', [ $this->funcionesajax, 'mostrarTablaReferralDetalleMesN']);
    add_action( 'wp_ajax_mostrarTablaReferralDetalleMesS', [ $this->funcionesajax, 'mostrarTablaReferralDetalleMesS']);
    add_action( 'wp_ajax_mostrarTablaRefRepMesSpe', [ $this->funcionesajax, 'mostrarTablaRefRepMesSpe']);
    add_action( 'wp_ajax_mostrarTablaRefRepMes', [ $this->funcionesajax, 'mostrarTablaRefRepMes']);
    add_action( 'wp_ajax_mostrarTablaReferralVarMes', [ $this->funcionesajax, 'mostrarTablaReferralVarMes']);
    add_action( 'wp_ajax_mostrarTablaReferralDetalleMesVar', [ $this->funcionesajax, 'mostrarTablaReferralDetalleMesVar']);
    add_action( 'wp_ajax_mostrarTablaReferralDetalleMesNFT', [ $this->funcionesajax, 'mostrarTablaReferralDetalleMesNFT']);
    add_action( 'wp_ajax_solicitar_agrdeposito',[ $this->funcionesajaxagre, 'solicitar_agrdeposito']);
    add_action( 'wp_ajax_solicitar_agrretiro',[ $this->funcionesajaxagre, 'solicitar_agrretiro']);
    add_action( 'wp_ajax_mostrarTablaAgrHistoDep', [ $this->funcionesajaxagre, 'mostrarTablaAgrHistoDep']);
    add_action( 'wp_ajax_mostrarTablaAgrHistoRet', [ $this->funcionesajaxagre, 'mostrarTablaAgrHistoRet']);
    add_action( 'wp_ajax_mostrarAgrTablaHistoDepFull', [ $this->funcionesajaxagre, 'mostrarAgrTablaHistoDepFull']);
    add_action( 'wp_ajax_mostrarAgrTablaHistoRetFull', [ $this->funcionesajaxagre, 'mostrarAgrTablaHistoRetFull']);
    add_action( 'wp_ajax_mostrarAgrTablaAdminDep', [ $this->funcionesajaxagre, 'mostrarAgrTablaAdminDep']);
    add_action( 'wp_ajax_mostrarAgrTablaAdminRet', [ $this->funcionesajaxagre, 'mostrarAgrTablaAdminRet']);
    add_action( 'wp_ajax_traer_datos_agre',[ $this->funcionesajaxagre, 'traer_datos_agre']);
    add_action( 'wp_ajax_operacion_editar_agre',[ $this->funcionesajaxagre, 'operacion_editar_agre']);
    add_action( 'wp_ajax_operacion_finalizar_agre',[ $this->funcionesajaxagre, 'operacion_finalizar_agre']);
    add_action( 'wp_ajax_operacion_cancelar_agre',[ $this->funcionesajaxagre, 'operacion_cancelar_agre']);
    add_action( 'wp_ajax_crear_opemaster_agre',[ $this->funcionesajaxagre, 'crear_opemaster_agre']);
    add_action( 'wp_ajax_mostrarTablaAgrHistoDepMas', [ $this->funcionesajaxagre, 'mostrarTablaAgrHistoDepMas']);
    add_action( 'wp_ajax_mostrarTablaAgrHistoRetMas', [ $this->funcionesajaxagre, 'mostrarTablaAgrHistoRetMas']);
    add_action( 'wp_ajax_mostrarAgrTablaDepMas', [ $this->funcionesajaxagre, 'mostrarAgrTablaDepMas']);
    add_action( 'wp_ajax_mostrarAgrTablaRetMas', [ $this->funcionesajaxagre, 'mostrarAgrTablaRetMas']);
    add_action( 'wp_ajax_traer_datos_agre_mas',[ $this->funcionesajaxagre, 'traer_datos_agre_mas']);
    add_action( 'wp_ajax_operacion_finalizar_agre_mas',[ $this->funcionesajaxagre, 'operacion_finalizar_agre_mas']);
    add_action( 'wp_ajax_traer_datos_registro_agr',[ $this->funcionesajaxagre, 'traer_datos_registro_agr']);
    add_action( 'wp_ajax_referral_agregarregistro_agr',[ $this->funcionesajaxagre, 'referral_agregarregistro_agr']);
    add_action( 'wp_ajax_mostrarTablaAdmAgrConMaster', [ $this->funcionesajaxagre, 'mostrarTablaAdmAgrConMaster']);
    add_action( 'wp_ajax_mostrarTablaAgrDetalleDepMes', [ $this->funcionesajaxagre, 'mostrarTablaAgrDetalleDepMes']);
    add_action( 'wp_ajax_mostrarTablaAgrDetalleDepMasMes', [ $this->funcionesajaxagre, 'mostrarTablaAgrDetalleDepMasMes']);
    add_action( 'wp_ajax_mostrarTablaAgrDetalleRetMes', [ $this->funcionesajaxagre, 'mostrarTablaAgrDetalleRetMes']);
    add_action( 'wp_ajax_mostrarTablaAgrDetalleRetMasMes', [ $this->funcionesajaxagre, 'mostrarTablaAgrDetalleRetMasMes']);
    add_action( 'wp_ajax_mostrarTablaAdmAgrInvMes', [ $this->funcionesajaxagre, 'mostrarTablaAdmAgrInvMes']);
    add_action( 'wp_ajax_mostrarTablaAgrListaUsuarios', [ $this->funcionesajaxagre, 'mostrarTablaAgrListaUsuarios']);
    add_action( 'wp_ajax_mostrarTablaAgrUserHistoDep', [ $this->funcionesajaxagre, 'mostrarTablaAgrUserHistoDep']);
    add_action( 'wp_ajax_mostrarTablaAgrUserHistoRet', [ $this->funcionesajaxagre, 'mostrarTablaAgrUserHistoRet']);
    add_action( 'wp_ajax_solicitar_condeposito',[ $this->funcionesajaxcons, 'solicitar_condeposito']);
    add_action( 'wp_ajax_solicitar_conretiro',[ $this->funcionesajaxcons, 'solicitar_conretiro']);
    add_action( 'wp_ajax_mostrarTablaConHistoDep', [ $this->funcionesajaxcons, 'mostrarTablaConHistoDep']);
    add_action( 'wp_ajax_mostrarTablaConHistoRet', [ $this->funcionesajaxcons, 'mostrarTablaConHistoRet']);
    add_action( 'wp_ajax_mostrarConTablaHistoDepFull', [ $this->funcionesajaxcons, 'mostrarConTablaHistoDepFull']);
    add_action( 'wp_ajax_mostrarConTablaHistoRetFull', [ $this->funcionesajaxcons, 'mostrarConTablaHistoRetFull']);
    add_action( 'wp_ajax_mostrarConTablaAdminDep', [ $this->funcionesajaxcons, 'mostrarConTablaAdminDep']);
    add_action( 'wp_ajax_mostrarConTablaAdminRet', [ $this->funcionesajaxcons, 'mostrarConTablaAdminRet']);
    add_action( 'wp_ajax_traer_datos_cons',[ $this->funcionesajaxcons, 'traer_datos_cons']);
    add_action( 'wp_ajax_operacion_editar_cons',[ $this->funcionesajaxcons, 'operacion_editar_cons']);
    add_action( 'wp_ajax_operacion_finalizar_cons',[ $this->funcionesajaxcons, 'operacion_finalizar_cons']);
    add_action( 'wp_ajax_operacion_cancelar_cons',[ $this->funcionesajaxcons, 'operacion_cancelar_cons']);
    add_action( 'wp_ajax_crear_opemaster_cons',[ $this->funcionesajaxcons, 'crear_opemaster_cons']);
    add_action( 'wp_ajax_mostrarTablaConUserDepMes', [ $this->funcionesajaxcons, 'mostrarTablaConUserDepMes']);
    add_action( 'wp_ajax_mostrarTablaConUserRetMes', [ $this->funcionesajaxcons, 'mostrarTablaConUserRetMes']);
    add_action( 'wp_ajax_mostrarTablaConListaUsuarios', [ $this->funcionesajaxcons, 'mostrarTablaConListaUsuarios']);
    add_action( 'wp_ajax_operacion_agregarconstatus',[ $this->funcionesajaxcons, 'operacion_agregarconstatus']);
    add_action( 'wp_ajax_operacion_elimstatus',[ $this->funcionesajaxcons, 'operacion_elimstatus']);
    add_action( 'wp_ajax_operacion_editstatus',[ $this->funcionesajaxcons, 'operacion_editstatus']);
  }

  private function definir_public_hooks(){
    add_filter( 'wp_mail_from', [ $this->redirect, 'custom_wp_mail_from'] );
    add_filter( 'wp_mail_from_name', [ $this->redirect, 'custom_wp_mail_from_name'] );
    add_filter( 'wp_mail_content_type', [ $this->redirect, 'crc_set_content_type'] );
    add_action( 'wp_enqueue_scripts', [ $this->agregarestilos, 'agregarscripts' ] );
    add_action( 'cmb2_init', [ $this->formularios, 'campos_formulario'] );
    add_shortcode( 'crc_registrar_usuario_shortcode', [ $this->formularios, 'formulario_registrar_usuario_shortcode'] );
    add_action('cmb2_after_init', [ $this->formularios, 'insertar_usuario'] );
    add_action( 'cmb2_after_form', [ $this->formularios, 'cmb2_after_form_do_js_validation'], 10, 2 );
    add_filter( 'template_include', [ $this->agregartemplates, 'agregartemplates'] );
    add_action( 'wp_ajax_nopriv_buscarUserRegEmail', [ $this->funcionesajax, 'buscarUserRegEmail'] );
    add_action( 'wp_ajax_buscarUserRegEmail', [ $this->funcionesajax, 'buscarUserRegEmail'] );
    add_action( 'wp_ajax_nopriv_buscarUserRegNick', [ $this->funcionesajax, 'buscarUserRegNick'] );
    add_action( 'wp_ajax_buscarUserRegNick', [ $this->funcionesajax, 'buscarUserRegNick'] );
    add_action( 'wp_ajax_nopriv_confirmSolicitud', [ $this->funcionesajax, 'confirmSolicitud'] );
    add_action( 'wp_ajax_confirmSolicitud', [ $this->funcionesajax, 'confirmSolicitud'] );
  }

  // Inicializador del objeto principal
  public function run(){
    $this->definir_admin_hooks();
    $this->definir_public_hooks();
  }
}

 ?>
