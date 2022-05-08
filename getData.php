<?
	$action = filter_input(INPUT_GET, "action");
switch ($action) {
    case "getData":
        getData();
        break;
    default:
        defaultFun();
}

function getData()
{
    $data = array(
        array(
            "id"=>"1" ,
            "itemName"=>"物品名稱1" ,
            "itemSelect"=>"選擇1" ,
        ),
        array(
            "id"=>"2" ,
            "itemName"=>"物品名稱2" ,
            "itemSelect"=>"選擇2" ,
        ),
        array(
            "id"=>"3" ,
            "itemName"=>"物品名稱3" ,
            "itemSelect"=>"選擇3" ,
        )
    );
    print_r(json_encode($data));
};

function defaultFun()
{
    $data[]=array(
            "id"=>"default" ,
            "itemName"=>"default" ,
            "itemSelect"=>"default"
        );
    print_r(json_encode($data));
}
?>