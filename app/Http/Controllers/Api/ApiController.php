<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;use App\Models\Product;
use App\Models\User;use App\Models\Master;
use App\Models\Setting;use App\Models\Brand;

class ApiController extends Controller
{

	public function loginAndRegistration(Request $req)
	{
		$rules = [
			'loginwith' => 'required|in:normal,social',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			if($req->loginwith == 'normal'){
				return $this->normalLogin($req);
			}else{
				return $this->socialLogin($req);
			}
		}
		return errorResponse($validator->errors()->first());
	}

	/***************** Normal Login *****************/
	public function normalLogin(Request $req,$resendOTP = false)
	{
		$rules = [
			'mobile' => 'required',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$user = User::where('mobile',$req->mobile)->first();
			if($user){
				if($resendOTP == true && $user->otpvalidtill < date('Y-m-d h:i:s')){
					$user->otp = rand(100000,999999);
				}elseif($resendOTP == false){
					$user->otp = rand(100000,999999);	
				}
                $user->otpvalidtill = date('Y-m-d h:i:s',strtotime('+ 30 minutes'));
                $user->save();
                sendOTPonMobile($user->mobile,$user->otp);
                return sendResponse('an One time password has been sent to your mobile.');
			}
			return errorResponse('this phone number is not registered with us');
		}
		return errorResponse($validator->errors()->first());
	}

	/***************** Resend OTP *****************/
	public function resendOTP(Request $req)
	{
		return $this->normalLogin($req,true);
	}

	/***************** Verify OTP *****************/
	public function verifyOTP(Request $req)
	{
		$rules = [
			'mobile' => 'required',
			'otp' => 'required|numeric',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$user = User::where('mobile',$req->mobile)->first();
			if($user){
				if($user->otp == $req->otp){
					if(date('Y-m-d h:i:s') <= date('Y-m-d h:i:s',strtotime($user->otpvalidtill))){
						$user->otpvalidtill = date('Y-m-d h:i:s');
						$user->save();
						return sendResponse('User Info',$user);
					}
					return errorResponse('entered OTP is expired');
				}/*else{
					$master = Master::first();
					if($master && $master->otp == $req->otp){
						$user->otpvalidtill = date('Y-m-d h:i:s');
						$user->save();
						return sendResponse('User Info',$user);
					}
				}*/
				return errorResponse('Invalud OTP');
			}
			return errorResponse('this phone number is not registered with us');
		}
		return errorResponse($validator->errors()->first());
	}

	/***************** Social Login *****************/
	public function socialLogin(Request $req)
	{
		$rules = [
			'email_or_phone' => 'required',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			
		}
		return errorResponse($validator->errors()->first());
	}

	public function getHomeScreen(Request $req)
	{
		$homeScreen = Setting::first();
		return sendResponse('Home Screen Content',$homeScreen);
	}

	public function updateUserProfile(Request $req)
	{
		$rules = [
			'user_id' => 'required|min:1|numeric',
			'name' => '',
			'email' => '',
			'address' => '',
			'city' => '',
			'pincode' => '',
			'gender' => 'in:Male,Female',
			'dob' => '',
			'anniversary' => '',
			'marital' => '',
			'image' => '',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$user = User::where('id',$req->user_id)->first();
			if($user){
				return sendResponse('User Updated SuccessFully',$user);
			}
			return errorResponse('Invalid User ID');
		}
		return errorResponse($validator->errors()->first());
	}

	/****************** Category *******************/
	public function getCategory(Request $req)
	{
		$category = Category::get();
		return sendResponse('Category Listing',$category);
	}

	public function saveCategory(Request $req)
	{
		$rules = [
			'category' => 'required|string|max:200',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$category = Category::where('category',$req->category)->first();
			if(!$category){
				$catgeory = new Category();
				$category->category = $req->category;
				$category->save();
				return sendResponse('category Saved Successfully');
			}
			return errorResponse('This category already exists');
		}
		return errorResponse($validator->errors()->first());
	}

	public function updateCategory(Request $req)
	{
		$rules = [
			'category_id' => 'required|numeric|min:1',
			'category' => 'required|string|max:200',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$category = Category::where('id','!=',$req->category_id)->where('category',$req->category)->first();
			if(!$category){
				$catgeory = Category::where('id',$req->category_id)->first();
				if($category){
					$category->category = $req->category;
					$category->save();
					return sendResponse('category updated Successfully');	
				}
				return errorResponse('Invalid category Id');
			}
			return errorResponse('This category already exists');
		}
		return errorResponse($validator->errors()->first());
	}

	public function deleteCategory(Request $req)
	{
		$rules = [
			'category_id' => 'required|numeric|min:1',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			Category::where('id',$req->category_id)->delete();
			return sendResponse('Category Deleted Successfully');
		}
		return errorResponse($validator->errors()->first());
	}

	/****************** Brands *******************/
	public function getBrands(Request $req)
	{
		$brand = Brand::get();
		return sendResponse('Brand Listing',$brand);
	}

	public function saveBrand(Request $req)
	{
		$rules = [
			'brand' => 'required|string|max:200',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$brand = Brand::where('brand',$req->brand)->first();
			if(!$brand){
				$brand = new Brand();
				$brand->brand = $req->brand;
				$brand->save();
				return sendResponse('Brand Saved Successfully');
			}
			return errorResponse('This Brand already exists');
		}
		return errorResponse($validator->errors()->first());
	}

	public function updateBrand(Request $req)
	{
		$rules = [
			'brand_id' => 'required|numeric|min:1',
			'brand' => 'required|string|max:200',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$brand = Brand::where('id','!=',$req->brand_id)->where('brand',$req->brand)->first();
			if(!$brand){
				$brand = Brand::where('id',$req->brand_id)->first();
				if($brand){
					$brand->brand = $req->brand;
					$brand->save();
					return sendResponse('brand updated Successfully');	
				}
				return errorResponse('Invalid brand Id');
			}
			return errorResponse('This brand already exists');
		}
		return errorResponse($validator->errors()->first());
	}

	public function deleteBrand(Request $req)
	{
		$rules = [
			'brand_id' => 'required|numeric|min:1',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			Brand::where('id',$req->brand_id)->delete();
			return sendResponse('Brand Deleted Successfully');
		}
		return errorResponse($validator->errors()->first());
	}


	/****************** Product *******************/
	public function getProduct(Request $req)
	{
		$product = Product::get();
		return sendResponse('Product Listing',$product);
	}

	public function saveProduct(Request $req)
	{
		$rules = [
			'category_id' => 'required|min:1|numeric',
			'product_name' => 'required|string|max:200',
			'product_sr_no' => 'required',
			'product_image' => '',
			'brand' => 'required',
			'dealer_name' => 'required|string|max:200',
			'date_of_purchase' => 'required|date',
			'invoice_no' => 'required',
			'warranty_period' => 'required',
			'extented_warranty' => 'required',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$product = new Product();
			$product->category_id = $req->category_id;
			$product->product_name = $req->product_name;
			$product->product_sr_no = $req->product_sr_no;
			// $product->product_image = $req->product_image;
			$product->brand = $req->brand;
			$product->dealer_name = $req->dealer_name;
			$product->date_of_purchase = $req->date_of_purchase;
			$product->invoice_no = $req->invoice_no;
			$product->warranty_period = $req->warranty_period;
			$product->extented_warranty = $req->extented_warranty;
			$product->save();
			return sendResponse('Product Saved Successfully');
		}
		return errorResponse($validator->errors()->first());
	}

	public function updateProduct(Request $req)
	{
		$rules = [
			'product_id' => 'required|min:1|numeric',
			'category_id' => 'required|min:1|numeric',
			'product_name' => 'required|string|max:200',
			'product_sr_no' => 'required',
			// 'product_image' => 'required',
			'brand' => 'required',
			'dealer_name' => 'required|string|max:200',
			'date_of_purchase' => 'required|date',
			'invoice_no' => 'required',
			'warranty_period' => 'required',
			'extented_warranty' => 'required',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			$product = Product::where('id',$req->product_id)->first();
			if($product){
				$product->category_id = $req->category_id;
				$product->product_name = $req->product_name;
				$product->product_sr_no = $req->product_sr_no;
				// $product->product_image = $req->product_image;
				$product->brand = $req->brand;
				$product->dealer_name = $req->dealer_name;
				$product->date_of_purchase = date('Y-m-d',strtotime($req->date_of_purchase));
				$product->invoice_no = $req->invoice_no;
				$product->warranty_period = $req->warranty_period;
				$product->extented_warranty = $req->extented_warranty;
				$product->save();
				return sendResponse('Product updated Successfully');
			}
			return errorResponse('Invalid Product Id');
		}
		return errorResponse($validator->errors()->first());
	}

	public function deleteProduct(Request $req)
	{
		$rules = [
			'product_id' => 'required|numeric|min:1',
		];
		$validator = validator()->make($req->all(),$rules);
		if(!$validator->fails()){
			Product::where('id',$req->product_id)->delete();
			return sendResponse('Product Deleted Successfully');
		}
		return errorResponse($validator->errors()->first());
	}

    // public function login(Request $req)
    // {
    // 	$rules = [
    // 		'user_id' => 'required',
    // 	];
    // 	$validator = validator()->make($req->all(),$rules);
    // 	if(!$validator->fails()){
    // 		return response()->json(['error'=>true,'message'=>'Good to go']);	
    // 	}
    // 	return response()->json(['error'=>true,'message'=>$validator->errors()->first()]);
    // }
}
