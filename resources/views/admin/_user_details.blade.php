<h2><span class="fa fa-user"></span> {!! $user->name !!} ({!! $user->email_address !!})</h2>

<dl class="dl-horizontal">
  <dt>Role</dt>
  <dd>{!! $user->role->role !!}</dd>
  
  <dt>Aleph ID</dt>
  <dd>{!! $user->aleph_id !!}</dd>
 
  <dt>Registered on</dt>
  <dd>{!! DateUtils::format($user->created_at) !!}</dd>

  <dt>Status</dt>
  <dd>
    @if($user->verified_user)
      Verified <span class="fa fa-check-square-o"></span>
    @else
      Not verified <span class="fa fa-square-o"></span>
    @endif
  </dd>
</dl>
