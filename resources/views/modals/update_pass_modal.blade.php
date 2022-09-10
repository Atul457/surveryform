 <!-- Modal -->
 <div
    class="modal fade text-start modal-primary"
    id="updatePassModal"
    tabindex="-1"
    aria-labelledby="myModalLabel160"
    aria-hidden="true"
>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel160">Update password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="#" id="updatePassForm">
            @csrf
            <div class="updatePassModalFields mb-1">
            <input 
                type="text" 
                name="password" 
                placeholder="New Password"
                id="upPass_password"
                class="form-control"/>
            </div>

            @if(session()->has('is_admin') == "1")
                @if(Session::get('is_admin') == 1)
                    <input type="hidden" id="is_admin" value="1">
                @else
                    <input type="hidden" id="is_admin" value="0">
                @endif
            @endif

            <div class="updatePassModalFields">
            <input 
                type="text" 
                name="cpassword"
                id="upPass_cpassword"
                placeholder="Confirm Password" 
                class="form-control"/>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button 
            type="button" 
            class="btn btn-primary update_pass_btn"
            id="updatePassBtn">
            Update
        </button>
    </div>
    </div>
</div>
</div>
<!-- Modals -->