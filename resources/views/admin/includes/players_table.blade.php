
    <table id="players_table" class="table table-striped"
           data-toolbar="#toolbar"
           data-search="true"
           data-show-refresh="true"
           data-show-toggle="true"
           data-show-columns="true"
           data-show-export="true"
           data-minimum-count-columns="2"
           data-show-pagination-switch="false"
           data-pagination="true"
           data-id-field="id"
           data-page-list="[10, 25, 50, 100, ALL]">
        <thead>
        <tr>
            <th data-formatter="indexTable" data-field="index" data-switchable="false" data-sortable="false">#</th>
            <th data-field="username" data-sortable="true">Username</th>
            <th data-field="player_position" data-sortable="true" >Position</th>
            <th data-field="skill_level" data-sortable="true">Skill</th>
            <th data-field="last_confirm" data-sortable="true">Confirm</th>
            <th data-field="status" data-sortable="true">Status</th>
            <th data-field="btn_update" class="text-center" data-switchable="false" data-sortable="false"></th>
            <th data-field="btn_status" class="text-center" data-switchable="false" data-sortable="false"></th>
        </tr>
        </thead>
    </table>