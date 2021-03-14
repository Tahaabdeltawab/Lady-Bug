@can('admin.manager')
{{--@hasrole('Admin')--}}
<li class="nav-item">
    <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
    <span class="menu-title">User Management</span>
    <i class="menu-arrow"></i>
    <i class="mdi mdi-account-multiple menu-icon"></i>
    </a>
    <div class="collapse" id="ui-basic">
    <ul class="nav flex-column sub-menu">
        <li class="nav-item"> 
            <a class="nav-link" href="{{ route('admin.user_management.user.index') }}">users</a>
        </li>
        <li class="nav-item"> 
            <a class="nav-link" href="{{ route('admin.user_management.role.index') }}">roles</a>
        </li>
        <li class="nav-item"> 
            <a class="nav-link" href="{{ route('admin.user_management.permission.index') }}">permissions</a>
        </li>
        <li class="nav-item"> 
            <a class="nav-link" href="{{ route('admin.user_management.department.index') }}">departments</a>
        </li>
    </ul>
    </div>
</li>
@endcan
{{--@endhasrole--}}




<li class="nav-item {{ Request::is('farms*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('farms.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Farms</span>
    </a>
</li>

<li class="nav-item {{ Request::is('workableRoles*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('workableRoles.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Workable Roles</span>
    </a>
</li>
<li class="nav-item {{ Request::is('workablePermissions*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('workablePermissions.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Workable Permissions</span>
    </a>
</li>
<li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('users.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Users</span>
    </a>
</li>
<li class="nav-item {{ Request::is('workableTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('workableTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Workable Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('workables*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('workables.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Workables</span>
    </a>
</li>
<li class="nav-item {{ Request::is('animalFodderSources*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('animalFodderSources.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Animal Fodder Sources</span>
    </a>
</li>
<li class="nav-item {{ Request::is('farmedTypeStages*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('farmedTypeStages.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Farmed Type Stages</span>
    </a>
</li>
<li class="nav-item {{ Request::is('farmActivityTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('farmActivityTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Farm Activity Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('chemicalFertilizerSources*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('chemicalFertilizerSources.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Chemical Fertilizer Sources</span>
    </a>
</li>
<li class="nav-item {{ Request::is('animalBreedingPurposes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('animalBreedingPurposes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Animal Breeding Purposes</span>
    </a>
</li>
<li class="nav-item {{ Request::is('homePlantIlluminatingSources*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('homePlantIlluminatingSources.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Home Plant Illuminating Sources</span>
    </a>
</li>
<li class="nav-item {{ Request::is('farmingMethods*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('farmingMethods.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Farming Methods</span>
    </a>
</li>
<li class="nav-item {{ Request::is('saltDetails*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('saltDetails.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Salt Details</span>
    </a>
</li>
<li class="nav-item {{ Request::is('animalFodderTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('animalFodderTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Animal Fodder Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('animalMedicineSources*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('animalMedicineSources.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Animal Medicine Sources</span>
    </a>
</li>
<li class="nav-item {{ Request::is('jobs*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('jobs.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Jobs</span>
    </a>
</li>
<li class="nav-item {{ Request::is('postTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('postTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Post Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('seedlingSources*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('seedlingSources.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Seedling Sources</span>
    </a>
</li>


<li class="nav-item {{ Request::is('measuringUnits*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('measuringUnits.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Measuring Units</span>
    </a>
</li>
<li class="nav-item {{ Request::is('buyingNotes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('buyingNotes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Buying Notes</span>
    </a>
</li>
<li class="nav-item {{ Request::is('information*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('information.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Information</span>
    </a>
</li>
<li class="nav-item {{ Request::is('weatherNotes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('weatherNotes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Weather Notes</span>
    </a>
</li>
<li class="nav-item {{ Request::is('soilTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('soilTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Soil Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('irrigationWays*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('irrigationWays.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Irrigation Ways</span>
    </a>
</li>
<li class="nav-item {{ Request::is('farmingWays*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('farmingWays.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Farming Ways</span>
    </a>
</li>
<li class="nav-item {{ Request::is('farmedTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('farmedTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Farmed Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('products*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('products.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Products</span>
    </a>
</li>
<li class="nav-item {{ Request::is('posts*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('posts.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Posts</span>
    </a>
</li>
<li class="nav-item {{ Request::is('comments*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('comments.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Comments</span>
    </a>
</li>
<li class="nav-item {{ Request::is('serviceTables*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('serviceTables.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Service Tables</span>
    </a>
</li>
<li class="nav-item {{ Request::is('serviceTasks*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('serviceTasks.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Service Tasks</span>
    </a>
</li>
<li class="nav-item {{ Request::is('farmedTypeClasses*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('farmedTypeClasses.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Farmed Type Classes</span>
    </a>
</li>
<li class="nav-item {{ Request::is('farmedTypeGinfos*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('farmedTypeGinfos.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Farmed Type Ginfos</span>
    </a>
</li>
<li class="nav-item {{ Request::is('chemicalDetails*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('chemicalDetails.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Chemical Details</span>
    </a>
</li>

<li class="nav-item {{ Request::is('cities*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cities.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Cities</span>
    </a>
</li>
<li class="nav-item {{ Request::is('districts*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('districts.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Districts</span>
    </a>
</li>
<li class="nav-item {{ Request::is('taskTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('taskTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Task Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('saltTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('saltTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Salt Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('locations*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('locations.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Locations</span>
    </a>
</li>
<li class="nav-item {{ Request::is('acidityTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('acidityTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Acidity Types</span>
    </a>
</li>
