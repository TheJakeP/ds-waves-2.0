<?php
namespace waves;
$label_style = "uppercase font-bold hex-646B6F text-base";


$url = "#";
$method = "post";
$form = new form($url, $method);

$form->set_classes_container("flex flex-col font-arial w-5/12 max-w-3xl text-xl justify-center content-center space-y-10");
$form->set_classes_form("space-y-5");
$form->set_classes_input("h-14");
$form->set_classes_label("uppercase font-bold hex-646B6F text-base");
$form->set_classes_select("h-14  max-h-14 w-full");
$form->set_classes_button("w-4/12 py-2");
$form->set_classes_textarea("bg-white border-solid border-menu-inactive border-2 rounded resize-none py-1 px-2 placeholder-waves-placeholder");

$form->add_title("Questions or Comments?", "font-waves-black text-2xl text-waves-black font-bold");
$form->add_input("FIRST NAME", "", "first_name");
$form->add_input("LAST NAME", "", "last_name");
$form->add_input("EMAIL", "", "email");
$select_values = [
    // "Formatted String"  => "value",
    "General Support"   => "support",
    "Report a Bug"      => "bug",
    "Other"             => "other",
];
$form->add_select("TOPIC", $select_values, "", "topic");
$form->add_textarea("Message", "", "support_message");
$form->add_blue_submit_button("Send Message", "submit");



?>
<div class="flex flex-col flex-wrap justify-center content-center ">
    <?php $form->show(); ?>
</div>