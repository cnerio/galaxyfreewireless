<?php 

function crudmoduleConfig($module){
    switch($module){
        case "packages":
            $config=[
                "formId"=>"packagesForm",
                "table"=>"package_records",
                "fields"=>[
                    "packageId"=>["label"=>"Package ID","name"=>"packageId","type"=>"number","class"=>"form-control","required"=>1],
                    "description"=>['label'=>"Description","name"=>"description","type"=>"text","class"=>"form-control","required"=>0],
                    "state"=>['label'=>"State","name"=>"state","type"=>"select","class"=>"form-select","required"=>1,"function"=>"getStates"],
                    "provider"=>["label"=>"Provider Name","name"=>"provider","type"=>"text","class"=>"form-control","required"=>0],
                    "providerId"=>["label"=>"Provider ID","name"=>"providerId","type"=>"number","class"=>"form-control","required"=>1],
                    "active"=>["label"=>"Active","name"=>"active","type"=>"select","options"=>["active"=>1,"inactive"=>'00'],"class"=>"form-select","required"=>0],

                ],
                "step"=>""
            ];
            break;
    }
}