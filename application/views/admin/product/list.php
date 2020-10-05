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
						<!-- <a href="<?php echo site_url('admin/products/add') ?>"><i class="fas fa-plus"></i> Add New</a> -->
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i>
						  Tambah Barang
						</button>
					</div>
					<div class="card-body">

						<div class="table-responsive">
							<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>Foto</th>
										<th>Nama Barang</th>
										<th>Harga Beli</th>
										<th>Harga Jual</th>
										<th>Stok</th>
										<!-- <th>Description</th> -->
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($products as $product): ?>
									<tr>
										<td>
											<img src="<?php echo base_url('upload/product/'.$product->image) ?>" width="64" />
										</td>
										<td width="150">
											<?php echo $product->name ?>
										</td>
										<td>
											<?php echo $product->harga_beli ?>
										</td>
										<td>
											<?php echo $product->harga_jual ?>
										</td>
										<td>
											<?php echo $product->stok ?>
										</td>
										<!-- <td class="small">
											<?php echo substr($product->description, 0, 120) ?>...</td> -->
										<td width="200">
											<!-- <a href="" data-toggle="modal" data-target="#exampleModal"
											 class="btn btn-success"><i class="fas fa-eye"></i> view</a> -->
											<a href="<?php echo site_url('admin/products/edit/'.$product->product_id) ?>"
											 class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
											<a onclick="deleteConfirm('<?php echo site_url('admin/products/delete/'.$product->product_id) ?>')"
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
											<a href="<?php echo site_url('admin/products/') ?>"><i class="fas fa-arrow-left"></i> Back</a>
										</div>
										<div class="card-body">
											<form action="<?php base_url('admin/product/add') ?>" method="post" enctype="multipart/form-data" >
												<div class="form-group">
													<label for="name">Nama Barang*</label>
													<input class="form-control <?php echo form_error('name') ? 'is-invalid':'' ?>"
													 type="text" name="name" placeholder="isi nama barang" required/>
													<div class="invalid-feedback">
														<?php echo form_error('name') ?>
													</div>
												</div>

												<div class="form-group">
													<label for="price">Stok</label>
													<input class="form-control <?php echo form_error('price') ? 'is-invalid':'' ?>"
													 type="number" name="stok" min="0" placeholder="isi dengan angka" required/>
													<div class="invalid-feedback">
														<?php echo form_error('price') ?>
													</div>
												</div>

												<div class="form-group">
													<label for="hargabeli">Harga Beli*</label>
													<input class="form-control"
													 type="number" name="harga_beli" placeholder="isi dengan angka" required/>
													<!-- <div class="invalid-feedback">
													</div> -->
												</div>

												<div class="form-group">
													<label for="hargajual">Harga Jual*</label>
													<input class="form-control"
													 type="number" name="harga_jual" placeholder="isi dengan angka" required/>
													<!-- <div class="invalid-feedback">
													</div> -->
												</div>

												<div class="form-group">
													<label for="image">Foto</label>
													<input class="form-control-file <?php echo form_error('image') ? 'is-invalid':'' ?>"
													 type="file" name="image" />
													<div class="invalid-feedback">
														<?php echo form_error('image') ?>
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
</body>

</html>
