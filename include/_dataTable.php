<? if ($_SESSION['user_type'].'' != 'student') { ?>
<div id="toolbar">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&nbsp;<i class="fas fa-house-damage"></i>&nbsp;</button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="#addModal" data-toggle="modal"><i class="fas fa-plus"></i> <span>New</span></a>
                <a class="dropdown-item" href="#deleteModal" id="btnDeleteSelected" data-toggle="modal"><i class="fa fa-trash-alt"></i> <span>Delete</span></a>
            </div>
        </div>
        <? if (isset($_page['editable']) && $_page['editable']){ ?>
            <button id="enable" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> <span>Enable Edit Mode</span></button>
        <? } ?>
    </div>
</div>
<? } ?>
<table class="table table-striped" id="table"></table>