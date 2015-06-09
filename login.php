<?php
/*
* Template Name: Login Page
*/
?>

<?php get_header(); ?>

<?php


?>

<?php
if ( is_user_logged_in() ) {
    echo 'Welcome, registered user!'; ?>
    <a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
<?php
} else {
    echo 'Welcome, visitor!';
    $page_id_of_member_area = 12; // current page in this case
    $args = array('redirect' => get_permalink( get_page( $page_id_of_member_area ) ) );

    if(isset($_GET['login']) && $_GET['login'] == 'failed')
    {
        ?>
            <div id="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;">
                <p>Login failed: You have entered an incorrect Username or password, please try again.</p>
            </div>
        <?php
    }

    wp_login_form( $args );
    wp_register();
    ?>
    <a href="<?php echo wp_registration_url(); ?>">Register</a>
    <a href="<?php echo wp_lostpassword_url(); ?>" title="Lost Password">Lost Password</a>
<?php 
}
?>
<?php 
get_sidebar();
get_footer(); 
?>