<!-- Main Sidebar Container -->
@php

@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
        <img src="{{asset('assets/dist/img/AdminLTELogo.png')}}" alt="User Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Medela</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Admin</a>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview @if(Route::currentRouteName()=='admin.dashboard'){{'menu-open'}}@endif">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link @if(Route::currentRouteName()=='admin.dashboard' 
                       || Route::currentRouteName()=='admin.settings' 
                       ){{'active'}}@endif">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            Home
                            <i class="right fas"></i>
                        </p>
                    </a>
                    <?php /*<ul class="nav nav-treeview @if(Route::currentRouteName()=='admin.dashboard' 
                    || Route::currentRouteName()=='admin.settings'){{'style="display: block;"'}}@endif">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.dashboard'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.settings'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        
                    </ul>*/ ?>
                </li>
                
                <?php /* ?><li class="nav-item has-treeview @if(
                            Route::currentRouteName()=='admin.user-management.site.user.list'||
                            Route::currentRouteName()=='admin.user-management.user-detail'){{'menu-open'}}@endif">
                    <a href="#"
                    class="nav-link @if(Route::currentRouteName()=='admin.user-management.user-detail' ||
                                      
                                        Route::currentRouteName()=='admin.user-management.site.user.list'){{'active'}}@endif">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview" @if(Route::currentRouteName()=='admin.user-management.site.user.list' ||
                                   
                                    Route::currentRouteName()=='admin.user-management.user-detail' || Route::currentRouteName()=='admin.user-management.site.user.admin.list' ||
                                   
                                    Route::currentRouteName()=='admin.user-management.user-admin-detail')
                                    style="display: block;"
                                    @endif>*/ ?>
                        

                            <?php /*<li class="nav-item">
                            <a href="{{route('admin.user-management.user.add')}}"
                             class="nav-link @if( \Route::currentRouteName()=='admin.user-management.user.add'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Admin Create</p>
                            </a>
                        </li> 
             
                         <li class="nav-item">
                            <a href="{{route('admin.user-management.user.list' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.user-management.user.list' 
                                        ||(Route::currentRouteName()=='admin.user-management.user-edit' && isset($details->usertype) && $details->usertype != 'FU')
                                      ){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Admin List</p>
                            </a>
                        </li>*/ ?>
                        
                        <?php /* <li class="nav-item">
                            <a href="{{route('admin.user-management.site.user.list' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.user-management.site.user.list' 
                                        || (Route::currentRouteName()=='admin.user-management.user-detail')
                                        ){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Loko User List</p>
                            </a>
                        </li> */ ?>

                        <?php /* <li class="nav-item">
                            <a href="{{route('admin.user-management.site.user.admin.list' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.user-management.site.user.admin.list' 
                                        || (Route::currentRouteName()=='admin.user-management.user-admin-detail')
                                        ){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Admin List</p>
                            </a>
                        </li> 
                    </ul>
                </li><?php */ ?>
               
                <?php /*<li class="nav-item has-treeview @if(
                            Route::currentRouteName()=='admin.warranty.site.warranty.list' ||
                            Route::currentRouteName()=='admin.warranty.warranty-add' ||
                            Route::currentRouteName()=='admin.warranty.serial-detail'
                            ){{'menu-open'}}@endif">*/ ?>
                            <li class="nav-item menu-open">
                    <a href="#"
                    class="nav-link @if(Route::currentRouteName()=='admin.package.site.package.list' ||
                            Route::currentRouteName()=='admin.package.package-edit'){{'active'}}@endif">
                    <i class="far fa-copy"></i>
                        <p>
                        Package Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    
                     <ul class="nav nav-treeview" @if(Route::currentRouteName()=='admin.package.site.package.list' ||
                            Route::currentRouteName()=='admin.package.package-edit')
                                    style="display: block;"
                                    @endif>
                        

                        <li class="nav-item">
                            <a href="{{route('admin.package.site.package.list' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.package.site.package.list' ||
                            Route::currentRouteName()=='admin.package.package-edit') {{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> List</p>
                            </a>
                        </li>

                        <?php /*<ul class="nav nav-treeview" style="display: block;">
                        

                        <li class="nav-item">
                            <a href="{{route('admin.rental.site.rental.list' )}}"
                             class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p> List</p>
                            </a>
                        </li> */ ?>
                         
                    </ul>

                    
                </li>
                <li class="nav-item menu-open">
                    <a href="#"
                    class="nav-link @if(Route::currentRouteName()=='admin.warranty.site.warranty.list' ||
                            Route::currentRouteName()=='admin.warranty.warranty-add'  ||
                            Route::currentRouteName()=='admin.warranty.serial-detail'){{'active'}}@endif">
                    <i class="far fa-bookmark"></i>
                        <p>
                        Rental Box Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview" @if(Route::currentRouteName()=='admin.warranty.site.warranty.list'  ||
                            Route::currentRouteName()=='admin.warranty.serial-detail')
                                    style="display: block;"
                                    @endif>

                        <li class="nav-item">
                            <a href="{{route('admin.warranty.site.warranty.list' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.warranty.site.warranty.list' ||
                            Route::currentRouteName()=='admin.warranty.serial-detail') {{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> List</p>
                            </a>
                        </li>
                         
                    </ul>

                    <ul class="nav nav-treeview" @if(Route::currentRouteName()=='admin.warranty.warranty-add')
                                    style="display: block;"
                                    @endif>
                        

                        <li class="nav-item">
                            <a href="{{route('admin.warranty.warranty-add' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.warranty.warranty-add') {{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Add</p>
                            </a>
                        </li> 
                         
                    </ul>
                </li>





                <li class="nav-item menu-open">
                    <a href="#"
                    class="nav-link @if(Route::currentRouteName()=='admin.rental.site.rental.list' ||
                            Route::currentRouteName()=='admin.rental.edit-rental'  ||
                            Route::currentRouteName()=='admin.rental.view-more'){{'active'}}@endif">
                    <i class="far fa-copy"></i>
                        <p>
                        Rental Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    
                     <ul class="nav nav-treeview" @if(Route::currentRouteName()=='admin.rental.site.rental.list' ||
                            Route::currentRouteName()=='admin.rental.edit-rental'  ||
                            Route::currentRouteName()=='admin.rental.view-more')
                                    style="display: block;"
                                    @endif>
                        

                        <li class="nav-item">
                            <a href="{{route('admin.rental.site.rental.list' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.rental.site.rental.list' ||
                            Route::currentRouteName()=='admin.rental.edit-rental'  ||
                            Route::currentRouteName()=='admin.rental.view-more') {{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> List</p>
                            </a>
                        </li>

                        <?php /*<ul class="nav nav-treeview" style="display: block;">
                        

                        <li class="nav-item">
                            <a href="{{route('admin.rental.site.rental.list' )}}"
                             class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p> List</p>
                            </a>
                        </li> */ ?>
                         
                    </ul>

                    
                </li>

               

               
                
                </li>

                




            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>