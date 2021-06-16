<!DOCTYPE html>
<html lang="en">

<head>
	<?php $this->load->view("admin/_partials/head.php") ?>
</head>

<body id="page-top">

	<?php $this->load->view("admin/_partials/navbar.php") ?>
	<div id="wrapper">

		<?php $this->load->view("admin/_partials/sidebar.php") ?>

		<div id="content-wrapper">

			<div class="container-fluid">

				<?php $this->load->view("admin/_partials/breadcrumb.php") ?>

				<?php if ($this->session->flashdata('failed')): ?>
				<div class="alert alert-danger" role="alert">
					<?php echo $this->session->flashdata('failed'); ?>
				</div>
				<?php endif; ?>

				<!-- DataTables -->
				<div class="card mb-3">
					<div class="card-header">
						<!-- <a href="<?php echo site_url('admin/comoditys/add') ?>"><i class="fas fa-plus"></i> Add New</a> -->
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i>
						  Tambah Comodity
						</button>
						<a href="<?php echo site_url('admin/comoditys/report') ?>"><button type="button" class="btn btn-primary"><i class="fas fa-download"></i>
						  Cetak Laporan
						</button></a>
					</div>
					<div class="card-body">

						<div class="table-responsive">
							<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>Nama</th>
										<th>Luas</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($comoditys as $comoditys): ?>
									<tr>
										<td width="150">
											<?php echo $comoditys->jenis ?>
										</td>
										<td width="150">
											<?php echo $comoditys->luas ?>
										</td>
										<td width="200">
											<!-- <a href="" data-toggle="modal" data-target="#exampleModal"
											 class="btn btn-success"><i class="fas fa-eye"></i> view</a> -->
											<a href="<?php echo site_url('admin/comoditys/edit/'.$comoditys->id) ?>"
											 class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
											<a onclick="deleteConfirm('<?php echo site_url('admin/comoditys/delete/'.$comoditys->id) ?>')"
											 href="#!" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</a>
										</td>
									</tr>
									<?php endforeach; ?>

								</tbody>
							</table>
						</div>
					</div>

					<!--modal-->
					<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					    	<div id="content-wrapper">

								<div class="container-fluid">

									<?php $this->load->view("admin/_partials/breadcrumb.php") ?>

									<?php if ($this->session->flashdata('success')): ?>
									<div class="alert alert-success" role="alert">
										<?php echo $this->session->flashdata('success'); ?>
									</div>
									<?php endif; ?>

									<div class="card mb-3">
										<div class="card-header">
											<a href="<?php echo site_url('admin/comoditys/') ?>"><i class="fas fa-arrow-left"></i> Back</a>
										</div>
										<div class="card-body">
											<form action="<?php base_url('admin/comoditys/add') ?>" method="post" enctype="multipart/form-data" >
												<div class="form-group">
													<label for="jenis">Nama*</label>
													<input class="form-control <?php echo form_error('jenis') ? 'is-invalid':'' ?>"
													 type="text" name="jenis" required/>
													<div class="invalid-feedback">
														<?php echo form_error('jenis') ?>
													</div>
												</div>

												<div class="form-group">
													<label for="luas">Luas*</label>
													<input class="form-control <?php echo form_error('luas') ? 'is-invalid':'' ?>"
													 type="number" name="luas" required/>
													<div class="invalid-feedback">
														<?php echo form_error('luas') ?>
													</div>
												</div>

												<input class="btn btn-primary" type="submit" name="btn" value="Save" />
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</form>

										</div>

										<div class="card-footer small text-muted">
											* required fields
										</div>
									</div>
									<!-- /.container-fluid -->
								</div>
								<!-- /.content-wrapper -->

							</div>
					      
					    </div>
					  </div>
					</div>
				</div>

			</div>
			<!-- /.container-fluid -->

			<!-- Sticky Footer -->
			<?php $this->load->view("admin/_partials/footer.php") ?>

		</div>
		<!-- /.content-wrapper -->

	</div>
	<!-- /#wrapper -->


	<?php $this->load->view("admin/_partials/scrolltop.php") ?>
	<?php $this->load->view("admin/_partials/modal.php") ?>

	<?php $this->load->view("admin/_partials/js.php") ?>

	<script>
	function deleteConfirm(url){
		$('#btn-delete').attr('href', url);
		$('#deleteModal').modal();
	}
	</script>
	<script type="text/javascript">
         $(function () {
             $('#datetimepicker1').datetimepicker();
         });
     </script>
</body>

</html>
