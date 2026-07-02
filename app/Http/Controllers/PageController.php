<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\HomeContentController;
use App\Models\Category;
use App\Models\ContentSetting;
use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'categories' => Category::withCount('products')->get(),
            'featuredProducts' => Product::with('category')->where('is_featured', true)->take(6)->get(),
            'homeContent' => ContentSetting::getValue('home', HomeContentController::defaults()),
        ]);
    }

    public function products(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('q'), fn($query) => $query->where(fn($q) => $q->where('product_code', 'like', '%' . $request->q . '%')->orWhere('product_name', 'like', '%' . $request->q . '%')))
            ->when($request->filled('category'), fn($query) => $query->whereHas('category', fn($q) => $q->where('slug', $request->category)))
            ->orderBy('final_price_usd')
            ->paginate(12)
            ->withQueryString();

        return view('pages.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'selectedCategory' => $request->category,
        ]);
    }

    public function product(Product $product)
    {
        $hideCommercial = config('emko.hide_commercial_values');
        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $comparisonGroups = $this->comparisonGroups();
        $comparisonProductSlugs = collect($comparisonGroups)
            ->flatMap(fn($group) => collect($group['columns'])->pluck('product_slug'))
            ->filter()
            ->unique()
            ->values();
        $comparisonProducts = Product::whereIn('slug', $comparisonProductSlugs)->get()->keyBy('slug');

        $comparisonGroups = collect($comparisonGroups)
            ->map(function ($group) use ($comparisonProducts, $product) {
                $columns = collect($group['columns'])->map(function ($column) use ($comparisonProducts) {
                    $column['product'] = $column['product_slug'] ? $comparisonProducts->get($column['product_slug']) : null;
                    return $column;
                })->values();

                $selectedIndex = $columns->search(fn($column) => optional($column['product'])->id === $product->id);
                if ($selectedIndex === false) {
                    return null;
                }

                $group['title'] = 'Spesifikasi ' . $product->product_name;
                $group['columns'] = collect([$columns[$selectedIndex]]);
                $group['rows'] = collect($group['rows'])->map(function ($row) use ($selectedIndex) {
                    return [$row[0], [$row[1][$selectedIndex] ?? '-']];
                })->values()->all();

                return $group;
            })
            ->filter()
            ->values();

        if ($comparisonGroups->isEmpty()) {
            $fallbackRows = [
                ['Product Code', [$product->product_code ?: '-']],
                ['Kategori', [$product->category->name]],
                ['Deskripsi', [$product->short_description ?: '-']],
                ['Harga Dasar', [$product->formatted_price_idr]],
                ['Diskon', [$hideCommercial ? '' : number_format($product->discount_percent, 0) . '%']],
                ['Harga Diskon', [$product->formatted_final_price_idr]],
                ['Status', [ucwords(str_replace('_', ' ', $product->status))]],
            ];

            foreach ($product->features ?? [] as $index => $feature) {
                $fallbackRows[] = ['Fitur ' . ($index + 1), [$feature]];
            }

            foreach ($product->specifications ?? [] as $index => $specification) {
                $fallbackRows[] = ['Spesifikasi ' . ($index + 1), [$specification]];
            }

            $comparisonGroups = collect([[
                'title' => 'Spesifikasi ' . $product->product_name,
                'columns' => collect([[
                    'label' => $product->product_name,
                    'product_slug' => $product->slug,
                    'product' => $product,
                ]]),
                'rows' => $fallbackRows,
            ]]);
        }

        return view('pages.products.show', compact('product', 'related', 'comparisonGroups'));
    }

    public function category(Category $category)
    {
        return view('pages.products.index', [
            'products' => $category->products()->with('category')->paginate(12),
            'categories' => Category::orderBy('name')->get(),
            'selectedCategory' => $category->slug,
            'categoryPage' => $category,
        ]);
    }

    public function pricelist()
    {
        return view('pages.pricelist', ['products' => Product::with('category')->orderBy('final_price_usd', 'desc')->get()]);
    }

    public function downloads()
    {
        return view('pages.downloads', ['products' => Product::with('category')->orderBy('product_name')->get()]);
    }

    public function solutions($slug = null)
    {
        return view('pages.solutions', ['slug' => $slug]);
    }

    public function articles()
    {
        return view('pages.articles');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    private function comparisonGroups(): array
    {
        return [
            [
                'title' => 'Series 1: Synchronizing, AMF, Mains, Midi, dan Mini AMF',
                'columns' => [
                    ['label' => 'Trans-AMF.SYNCRO', 'product_slug' => 'trans-amf-synchro'],
                    ['label' => 'Trans-MAINS', 'product_slug' => 'trans-mains'],
                    ['label' => 'Trans-SYNCRO', 'product_slug' => 'trans-synchro'],
                    ['label' => 'Trans-AMF', 'product_slug' => 'trans-amf'],
                    ['label' => 'Trans-Midi.AMF', 'product_slug' => 'trans-midi-amf'],
                    ['label' => 'Trans-Mini.AMF', 'product_slug' => 'trans-mini-amf'],
                ],
                'rows' => [
                    ['Dimensions', ['276x189mm', '276x189mm', '276x189mm', '229x152mm', '158x111mm', '111 x 81mm']],
                    ['Synchronising', ['Mains & Gen', 'Mains', 'Gen', 'Synchronization Check', 'Synchronization Check', '-']],
                    ['Mains Voltage', ['3Ph', '3Ph', '-', '3Ph', '3Ph', '3Ph']],
                    ['Gen Voltage', ['3Ph', '-', '3Ph', '3Ph', '3Ph', '1Ph']],
                    ['Current', ['3Ph Gen<br>1Ph Mains', '3Ph Mains<br>1Ph Load', '3Ph Gen', '3Ph Gen', '3Ph Gen', '1Ph Gen']],
                    ['Mag.PickUp', ['+', '-', '+', '+', '/+', '/+']],
                    ['CanBus J-1939', ['+', '-', '+', '+', '/+', '/+']],
                    ['Configurable Inputs', ['13', '13', '13', '7', '5', '3']],
                    ['Remote Start Input', ['conf', '-', 'conf', 'conf', 'conf', 'conf']],
                    ['Oil Pressure Input', ['Sender or<br>Alarm', '-', 'Sender or<br>Alarm', 'Sender or<br>Alarm', 'Sender or<br>Alarm', 'Sender or<br>Alarm']],
                    ['Temperature Input', ['Sender or<br>Alarm', '-', 'Sender or<br>Alarm', 'Sender or<br>Alarm', 'Sender or<br>Alarm', 'Sender or<br>Alarm']],
                    ['Aux. Sender Input', ['2', '-', '2', '2', '1', '1']],
                    ['Charg.Alt.Input', ['+', '-', '+', '+', '+', '+']],
                    ['Configure Outputs', ['7', '9', '7', '4', '4', '4']],
                    ['Crank output', ['+', '-', '+', '1', '1', '1']],
                    ['Solenoid output', ['+', '-', '+', '1', '1', '1']],
                    ['MCB out', ['+', '+', '-', '+', '+', '+']],
                    ['GCB out', ['+', '-', '+', '+', '+', '+']],
                    ['Communication', ['USB, Ethernet<br>RS485', 'USB, CanOpen<br>Ethernet,RS485', 'USB, CanOpen<br>Ethernet,RS485', 'USB', 'RS232<br>RS485', 'RS-232']],
                    ['I/O Expansion Module', ['/+', '/+', '/+', '/+', '-', '-']],
                    ['Remote monitoring Modules', ['Messenger<br><br>WEB-Ethernet<br><br>WEB-GPRS<br><br>GPRS', 'Messenger<br><br>WEB-Ethernet<br><br>WEB-GPRS<br><br>GPRS', 'Messenger<br><br>WEB-Ethernet<br><br>WEB-GPRS<br><br>GPRS', 'Messenger<br><br>WEB-Ethernet<br><br>WEB-GPRS<br><br>GPRS', 'N/A', 'N/A']],
                ],
            ],
            [
                'title' => 'Series 2: Auto, ATS, Mini Auto, dan Mini Pump',
                'columns' => [
                    ['label' => 'Trans-Auto', 'product_slug' => 'trans-auto'],
                    ['label' => 'Trans-Midi.Auto', 'product_slug' => 'trans-midi-auto'],
                    ['label' => 'Trans-Mini.Auto', 'product_slug' => null],
                    ['label' => 'Trans-Mini Auto++', 'product_slug' => 'trans-mini-auto'],
                    ['label' => 'Trans-ATS', 'product_slug' => 'trans-ats-d'],
                    ['label' => 'Trans-Mini.ATS', 'product_slug' => 'trans-mini-ats'],
                    ['label' => 'Trans-MiniPump', 'product_slug' => 'trans-mini-pump'],
                ],
                'rows' => [
                    ['Dimensions', ['229x152mm', '158x111mm', '111x81mm', '111x81mm', '229x152mm', '111x81mm', '111x81mm']],
                    ['Synchronising', ['-', '-', '-', '-', '-', '-', '-']],
                    ['Mains Voltage', ['N/A', 'N/A', 'N/A', 'N/A', '3Ph', '3Ph', '-']],
                    ['Gen Voltage', ['3Ph', '3Ph', '1Ph', '3Ph', '3Ph', '1Ph', '-']],
                    ['Current', ['3Ph Gen', '3Ph Gen', '1Ph Gen', '3Ph Gen', '3Ph Load', '1Ph Load', '-']],
                    ['Mag.PickUp', ['+', '/+', '/+', '/+', '-', '-', '+']],
                    ['CanBus J-1939', ['+', '/+', '/+', '/+', '-', '-', '-']],
                    ['Configurable Inputs', ['7', '5', '3', '3', '8', '5', '3']],
                    ['Remote Start Input', ['conf', 'conf', 'conf', 'conf', '-', '-', '1']],
                    ['Oil Pressure Input', ['Sender or<br>Alarm', 'Sender or<br>Alarm', 'Sender or<br>Alarm', 'Sender or<br>Alarm', 'N/A', 'N/A', 'Sender or<br>Alarm']],
                    ['Temperature Input', ['Sender or<br>Alarm', 'Sender or<br>Alarm', 'Sender or<br>Alarm', 'Sender or<br>Alarm', 'N/A', 'N/A', 'Sender or<br>Alarm']],
                    ['Aux. Sender Input', ['2', '1', '1', '1', '-', '-', '1']],
                    ['Charg.Alt.Input', ['+', '+', '+', '+', 'N/A', 'N/A', '1']],
                    ['Configure Outputs', ['4', '4', '4', '4', '6', '6', '5']],
                    ['Crank output', ['1', '1', '1', '1', 'N/A', 'N/A', '1']],
                    ['Solenoid output', ['1', '1', '1', '1', 'N/A', 'N/A', '1']],
                    ['MCB out', ['N/A', 'N/A', 'N/A', 'N/A', '+', '+', '-']],
                    ['GCB out', ['+', '+', 'conf', '+', '+', '+', '-']],
                    ['Communication', ['USB', 'RS-232<br>RS-485', 'RS-232', 'RS-232', 'USB', 'RS-232', 'RS-232']],
                    ['I/O Expansion Module', ['/+', '-', '-', '-', '/+', '-', '-']],
                    ['Remote monitoring Modules', ['Messenger<br><br>WEB-Ethernet<br><br>WEB-GPRS<br><br>GPRS<br><br>RS-485', 'N/A', 'N/A', 'N/A', 'Messenger<br><br>WEB-Ethernet<br><br>WEB-GPRS<br><br>GPRS<br><br>RS-485', 'N/A', 'N/A']],
                ],
            ],
        ];
    }
}
