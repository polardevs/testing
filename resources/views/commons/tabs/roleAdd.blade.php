<div class="tab-pane {{ @$active }}">
  <div class="box-body table-responsive">

    {{--
    <div class="form-group">
      <label>Add new role</label>
      <form action="{{ action('RoleController@store') }}" method="POST" role="form">
        {!! csrf_field() !!}
        <div class="input-group input-group-sm">
          <input name="name" type="text" class="form-control" placeholder="e.g.: super admin, admin ... or ... manager">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-info btn-flat">ADD</button>
            </span>
        </div>
      </form>
    </div>
    --}}

    <!-- Roles table and trigger modal -->
    <h3><strong>Roles</strong></h3>
    <table class="table">
      <tr>
        <th style="width: 10px">#</th>
        <th>Name</th>
        <th style="width: 40px">Permission</th>
        <th style="width: 40px">Status</th>
        {{--<th style="width: 40px">Action</th>--}}
      </tr>
      @foreach($roles as $index => $role)
        <tr>
          <td>{{ $index + 1 }}.</td>
          {{--<td><span style="cursor:pointer;" data-toggle="modal" data-target="#roleModalEdit{{ $role->id }}">{{ $role->name }}</span></td>--}}
          <td><span>{{ $role->name }}</span></td>
          <td><span class="badge bg-light-blue">{{ $role->permissions->count() }}</span></td>
          @if($role->active)
            <td><span class="label label-success">Approved</span></td>
          @else
            <td><span class="label label-danger">Denied</span></td>
          @endif
          {{--
          <td>
            @if($role->permissions->count())
              <span class="label label-warning" data-toggle="tooltip" title="It's has permission" style="cursor:not-allowed;">Delete</span>
            @else
              <span class="label label-warning" style="cursor:pointer;" onclick="$(this).find('form').submit();">
                Delete
                <form action="{{ action('RoleController@destroy', $role->id) }}" method="POST" hidden>
                  <input type="hidden" name="_method" value="delete">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
                </form>
              </span>
            @endif
          </td>
          --}}
        </tr>
      @endforeach
    </table>

    <!-- Modal -->
    @foreach($roles as $role)
      <div class="modal fade" id="roleModalEdit{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="roleModalEdit{{ $role->id }}Label">
        <div class="modal-dialog" role="document">
          <form action="{{ action('RoleController@update', $role->id) }}" method="POST" role="form">
            {!! method_field('put') !!}
            {!! csrf_field() !!}
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><strong>Role</strong>: {{ $role->name }}</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Role name</label>
                  <input name="name" type="text" class="form-control" placeholder="Enter role name" value="{{ $role->name or '' }}">
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="active" {{ $role->active == true ? 'checked' : '' }}> active this role
                  </label>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</div>

@section('scripts')
  @parent
  <script type="text/javascript">
    $(function () {
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });
    });
  </script>
@endsection
