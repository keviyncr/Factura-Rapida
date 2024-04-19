
<!-- begin #footer -->
<div id="footer" class="footer text-center">
    &copy; 2020 Contafast S.A - All Rights Reserved
</div>
<!-- end #footer -->

<!-- modal para primer inicio -->
<div class="modal modal-message" id="company-Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Listado de compañias.</h4>
            </div>
            <div class="modal-body">

                <form action="{{ route('changeCompany') }}" method="POST"  class="form-horizontal" enctype="multipart/form-data" name="form-wizar" class="form-control-with-bg">
                    @csrf
                    <form data-parsley-validate="true">

                        <div class="form-group row m-b-15">
                            <label class="col-md-4 col-sm-4 col-form-label">Seleccione una compañia :</label>
                            <div class="col-md-8 col-sm-8">
                                <select class="default-select4 form-control" id="select-required" name="sl_company" data-parsley-required="true">
                                    @if(session('company')!=null)
                                    @foreach(session('companies') as $compani)
                                    <option style="color: black;" value="{{ $compani->id }}">{{ $compani->id_card." - ".$compani->name_company }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group row m-b-0">
                            <label class="col-md-4 col-sm-4 col-form-label">&nbsp;</label>
                            <div class="col-md-8 col-sm-8">
                                <button type="submit" class="btn btn-danger" style="width: 100%">Cambiar</button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<!-- modal para primer inicio -->
<div class="modal modal-message" id="modal-term">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Terminos y condiciones.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <fieldset>
                    <iframe src="{{ asset('laravel/storage/app/public//Files/Terminos y Condiciones Factura Rapida.pdf') }}" width="100%" height="600px">
                    </iframe>	
                </fieldset>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>
