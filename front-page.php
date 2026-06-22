<?php
/**
 * Página inicial (Configurações → Leitura: quando “Página inicial” = uma página estática).
 *
 * Deve usar o mesmo layout do blog com bloco Novidades; sem isto o tema mostrava só o
 * conteúdo da página (ou lista genérica), não o design em template-parts/home-sections.php.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/home', 'sections' );
get_footer();
