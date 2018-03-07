<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;
use App\Product;
use App\ProductType;
use App\Cart;
use Session;
use App\Customer;
use App\Bill;
use App\BillDetail;

class PageController extends Controller
{
    //
    public function getIndex(){
    	$slide = Slide::all();
    	//return view('page.trangchu',['slide'=>$slide]);
    	$new_product = Product::where('new',1)->paginate(4);
    	$sanpham_khuyenmai = Product::where('promotion_price','<>',0)->paginate(8);
    	return view('page.trangchu',compact('slide','new_product','sanpham_khuyenmai'));
    }

    public function getLoaiSp($type){
    	// lay sp theo loai
    	$sp_theoloai = Product::where('id_type',$type)->get();
    	// phan trang san pham
    	$sp_khac = Product::where('id_type','<>',$type)->paginate(3);
    	$loai = ProductType::all();
    	$loai_sp = ProductType::where('id',$type)->first();
    	return view('page.loai_sanpham',compact('sp_theoloai','sp_khac','loai','loai_sp'));
    }

    public function getChitiet(Request $res){
    	$sanpham = Product::where('id',$res->id)->first();
    	$sp_tuongtu = Product::where('id_type',$sanpham->id_type)->paginate(3);
    	return view('page.chitiet_sanpham',compact('sanpham','sp_tuongtu'));
    }

    public function getLienHe(){
    	return view('page.lienhe');
    }

    public function getAbout(){
    	return view('page.about');
    }

    public function getAddToCart(Request $res,$id){
    	$product = Product::find($id);
    	$oldCart = Session('cart')?Session::get('cart'):null;
    	$cart = new Cart($oldCart);
    	$cart->add($product, $id);
		$res->session()->put('cart',$cart);
		return redirect()->back(); 
    }

    public function getDetailItemCart($id){
    	$oldCart = Session::has('cart')?Session::get('cart'):null;
    	 $cart = new Cart($oldCart);
    	 $cart->removeItem($id);
    	 if(count($cart->items)>0){
    	 	Session::put('cart', $cart);
    	 }
    	 else{
    	 	Session::forget('cart');
    	 }
    	 return redirect()->back();
    }

    public function getCheckout(){
    	return view('page.dat_hang');
    }

    public function postCheckout(Request $res){
    	$cart = Session::get('cart');
    	$customer = new Customer;
    	$customer->name = $res->name;
    	$customer->gender = $res->gender;
    	$customer->email = $res->email;
    	$customer->address = $res->address;
    	$customer->phone_number = $res->phone;
    	$customer->note = $res->note;
    	$customer->save();

    	$bill = new Bill;
    	$bill->id_customer = $customer->id;
    	$bill->date_order = date('Y-m-d');
    	$bill->total = $cart->totalPrice;
    	$bill->payment = $res->payment;
    	$bill->note = $res->note;
    	$bill->save();

    	foreach ($cart['items'] as $key => $value) {
    		$bill_detail = new BillDetail;
    		$bill_detail->id_bill = $bill->id;
    		$bill_detail->id_product = $key;
    		$bill_detail->quantity = $value['qty'];
    		$bill_detail->unit_price = ($value['price']/$value['qty']);
    		$bill_detail->save();
    	}
    	Session::forget('cart');
    	return redirect()->back()->with('thongbao','Đặt hàng thành công');
    	
    }
}
