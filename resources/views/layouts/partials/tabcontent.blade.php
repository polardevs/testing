<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="@if($activeTab == 'timeline') active @endif">
      <a href="#timeline" class="active" data-toggle="tab">Timeline</a>
    </li>
    <li class="@if($activeTab == 'timewait') active @endif">
      <a href="#timewait" data-toggle="tab">wait to approve</a>
    </li>
    <li class="@if($activeTab == 'timesheet') active @endif">
      <a href="#timesheet" data-toggle="tab">Timesheet</a>
    </li>
  </ul>
  <div class="tab-content">
    @include('commons.tabs.timeline',  ['active' => ($activeTab == 'timeline' ? 'active' : '')])
    @include('commons.tabs.timesheet', ['active' => ($activeTab == 'timesheet' ? 'active' : '')])
    @include('commons.tabs.timewait',  ['active' => ($activeTab == 'timewait' ? 'active' : '')])
  </div>
</div>
