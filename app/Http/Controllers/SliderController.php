<?php

	namespace App\Http\Controllers;

	use App\Models\Slider;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;

	class SliderController extends Controller
	{

        function __construct()
        {
            /* INSERT INTO `permissions`
             (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
             VALUES
             (NULL, 'slider-menu', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
             (NULL, 'slider-create', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
             (NULL, 'slider-edit', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
             (NULL, 'slider-list', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59'),
             (NULL, 'slider-delete', 'web', '2023-05-05 11:48:59', '2023-05-05 11:48:59') */


             $this->middleware('permission:slider-list|slider-create|slider-edit|slider-delete', ['only' => ['index','show']]);
             $this->middleware('permission:slider-create', ['only' => ['create','store']]);
             $this->middleware('permission:slider-edit', ['only' => ['edit','update',]]);
             $this->middleware('permission:slider-delete', ['only' => ['destroy']]);
        }
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			$find = (new Slider())->ListaSlider()->get();

			return view('empresas.slider.index', compact('find'));
		}

		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$slider = new Slider();
			$empresas = $slider->ListaEmpresas();
			return view('empresas.slider.nuevo', compact('slider', 'empresas'));
		}

		public function editar($id)
		{
			$slider = slider::find($id);
			$empresas = $slider->ListaEmpresas();
			$dataEmpresa = $slider->EmpresaData();

			if (!empty($slider)) {
				return view('empresas.slider.nuevo', compact('slider', 'empresas', 'dataEmpresa'));
			} else {
				return view('error.504');
			}
		}

		public function store(Request $r)
		{
			$slider = new Slider();

			if ($r->id != null) {

				$find = Slider::find($r->id);
				if (!empty($find)) {
					$slider = $find;

				}
			}
			if ($r->hasFile('imagen')) {
				$dir = 'uploads/slider/';
				$extension = strtolower($r->file('imagen')->getClientOriginalExtension()); // get image extension
				$fileName = time() . '_.' . $extension; // rename image
				$r->file('imagen')->move($dir, $fileName);
				$slider->imagen = $dir . $fileName;
			}

			$slider->titulo_en = $r->titulo_en;
			$slider->titulo_es = $r->titulo_es;
			$slider->descripcion_es = $r->descripcion_es;
			$slider->descripcion_en = $r->descripcion_en;
			$slider->estatus = $r->estatus;
			$slider->empresas_id = (int)$r->empresa_id;
			$slider->push();

			if ($r->id != null) {
				return redirect()->route('slider')->with('success', trans('empresas.succes_update_slider'));
			} else {
				return redirect()->route('slider')->with('success', trans('empresas.succes_insert_slider'));
			}

			//
		}

		public function delete($id)
		{
			$slider = Slider::find($id);
			if (!empty($slider)) {
				$slider->delete();
				return redirect()->back()->with('success', trans('empresas.succes_delete_slider'));
			} else {
				return redirect()->back()->with('error', trans('empresas.error_delete_slider'));
			}
		}

		public function papelera()
		{

			$find = Slider::onlyTrashed()->ListaSlider()->get();
			return view('empresas.slider.trash', compact('find'));

		}

		public function restore($id)
		{

			$slider = Slider::withTrashed()->where('id', $id);
			if (!empty($slider)) {
				$slider->restore();
				return redirect()->back()->with('success', trans('empresas.succes_restore_slider'));
			} else {
				return redirect()->back()->with('error', trans('empresas.error_restore_slider'));
			}

		}
	}
