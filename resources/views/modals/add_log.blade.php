<div class="modal fade" id="add-log" tabindex="-1" role="dialog" aria-labelledby="add-log" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="add-coaching-log-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="exampleModalLabel-2">Create New Coaching Log</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row p-5">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6 ">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Agent Name  <span class="text-danger">*</span></label>
                                    <select class="form-control-sm select2bs4" style="width: 100%;" name="agent_id">
                                    @if ($usersWithTeam->isNotEmpty())
                                        @foreach ($usersWithTeam as $user)
                                            <option value="{{$user->id}}" data-team-id="{{ $user->team_id }}">{{$user->lastname}}, {{$user->firstname}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="fw-bold">Category <span class="text-danger">*</span></label>
                                    <select class="form-control-sm" name="category_id">
                                        <option value="">Select Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group ">
                                    <label class=" fw-bold">Coaching Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-sm" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="mm/dd/yyyy" name="date_coached" min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="fw-bold">Channel <span class="text-danger">*</span></label>
                                    <select class="form-control-sm" name="channel" id="channel">
                                        <option value="">Select Channel</option>
                                        <option value="Face to Face">Face to Face</option>
                                        <option value="Online Meeting">Online Meeting</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label for="name"><i class="fa fa-question-circle" data-toggle="tooltip" data-html="true" title="Purpose of discussion. Desired outcome/goal. Set importance and benefits"></i> Goal <span class="text-danger">*</span></label>
                        <textarea name="goal" class="summernote" ></textarea>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center">
                        <div class="form-group col-lg-12">
                            <label for="name">Reality <span class="text-danger">*</span></label>
                            <textarea name="reality" class="summernote"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">    
                        <div class="form-group col-lg-12">
                            <label for="name">Option <span class="text-danger">*</span></label>
                            <textarea name="option" class="summernote"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({
                html: true
            })
        })
    });
</script>