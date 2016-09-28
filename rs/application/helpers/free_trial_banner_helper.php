<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function free_trial_banner(){
    $ci =& get_instance();
    $user = $ci->session->userdata('user');

    if($user){

        $company = $ci->db->get_where('wp_company',array('id' => $user->company_id),1,0)->row();

        /*if in trial period?*/
        $date1=date_create(date('Y-m-d'));
        $date2=date_create($company->created);
        $diff=date_diff($date2,$date1);
        $days = $diff->format("%a");

        /*should not be a williams company and will have to be in trial period*/
        if(!in_array($company->id, array(31,  34, 24, 26, 27, 28, 30, 38, 61)) && !$company->payment_token  && $days <= 30){

            $date1=date_create(date('Y-m-d'));
            $date2=date_create($company->next_payment_date);
            $diff=date_diff($date1,$date2);
            $days_left = $diff->format("%a");

            if($user->role == 1){

                $banner = "<div style='width: 100%; padding: 2px 0px; text-align: center; background-color: rgb(120, 120, 120); color: white; font-weight: bold;'>You have {$days_left} days FREE trial left. <a style='color: white' href='https://".$company->url.'/user/select_plan'."'>Click here</a> to enjoy our systems even more.</div>";
            }else{
                $banner = "<div style='width: 100%; padding: 2px 0px; text-align: center; background-color: rgb(120, 120, 120); color: white; font-weight: bold;'>You have {$days_left} days FREE trial left. Log in as an admin to fully enjoy our systems.</div>";
            }
            echo $banner;
        }
    }
}