<?php

class ColorClass {
    public $r;
    public $g;
    public $b;
}

class VectorClass {
	public $x;
	public $y;
	public $z;

	public function __construct(){
        $x = 0;
        $y = 0;
        $z = 0;
    }

    public static function vinit($i, $j, $k){
        $x = $i;
        $y = $j;
        $z = $k;
    }

	public function getVectX() { return $x; }
	public function getVectY() { return $y; }
	public function getVectZ() { return $z; }
	
	public function magnitude () {
		return sqrt(($x*$x) + ($y*$y) + ($z*$z));
	}
	
	public function normalize () {
		$magnitude = sqrt(($x*$x) + ($y*$y) + ($z*$z));
		return VectorClass::vinit($x/$magnitude, $y/$magnitude, $z/$magnitude);
	}
	
	public function negative () {
		return VectorClass::vinit(-$x, -$y, -$z);
	}
	
	public function dotProduct($v) {
		return $x*$v.getVectX() + $y*$v.getVectY() + $z*$v.getVectZ();
	}
	
	public function crossProduct($v) {
		return VectorClass::vinit($y*$v.getVectZ() - $z*$v.getVectY(), $z*$v.getVectX() - $x*$v.getVectZ(), $x*$v.getVectY() - $y*$v.getVectX());
	}
	
	public function vectAdd ($v) {
		return VectorClass::vinit($x + $v.getVectX(), $y + $v.getVectY(), $z + $v.getVectZ());
	}

	public function vectMult ($scalar) {
		return VectorClass::vinit($x*$scalar, $y*$scalar, $z*$scalar);
	}
};

class Ray {
	public $origin;
	public $direction;
	
	public function __construct(){
        $origin = new VectorClass();
        $direction = VectorClass::vinit(1,0,0);
    }

    public static function rinit($i, $j){
        $origin; = $i;
        $direction = $j;
    }
	
	public function getRayOrigin () { return $origin; }
	public function getRayDirection () { return $direction; }
	
};

class Camera {
	public $campos;
	public $camdir;
	public $camright;
	public $camdown;
	
	public function __construct(){
		$campos = new VectorClass();
		$camdir = VectorClass::vinit(0,0,1);
		$camright = new VectorClass();
		$camdown = new VectorClass();
    }

    public static function cinit($i, $j, $k, $l){
		$campos = $i;
		$camdir = $j;
		$camright = $k;
		$camdown = $l;
    }
	
	public function getCameraPosition () { return $campos; }
	public function getCameraDirection () { return $camdir; }
	public function getCameraRight () { return $camright; }
	public function getCameraDown () { return $camdown; }
	
};

class Color {
	public $red;
	public $green;
	public $blue;
	public $special;
	
	public function __construct(){
		$red = 0.5;
		$green = 0.5;
		$blue = 0.5;
    }

    public static function coinit($r, $g, $b, $s){
		$red = $r;
		$green = $g;
		$blue = $b;
		$special = $s;
    }
	
	public function getColorRed() { return $red; }
	public function getColorGreen() { return $green; }
	public function getColorBlue() { return $blue; }
	public function getColorSpecial() { return $special; }
	
	public function setColorRed($redValue) { $red = $redValue; }
	public function setColorGreen($greenValue) { $green = $greenValue; }
	public function setColorBlue($blueValue) { $blue = $blueValue; }
	public function setColorSpecial($specialValue) { $special = $specialValue; }
	
	public function brightness() {
		return($red + $green + $blue)/3;
	}
	
	public function colorScalar($scalar) {
		return Color::coinit($red*$scalar, $green*$scalar, $blue*$scalar, $special);
	}
	
	public function colorAdd($color) {
		return Color::coinit($red + $color.getColorRed(), $green + $color.getColorGreen(), $blue + $color.getColorBlue(), $special);
	}
	
	public function colorMultiply($color) {
		return Color::coinit($red*$color.getColorRed(), $green*$color.getColorGreen(), $blue*$color.getColorBlue(), $special);
	}
	
	public function colorAverage($color) {
		return Color::coinit(($red + $color.getColorRed())/2, ($green + $color.getColorGreen())/2, ($blue + $color.getColorBlue())/2, $special);
	}
	
	public function clip() {
		$alllight = $red + $green + $blue;
		$excesslight = $alllight - 3;
		if ($excesslight > 0) {
			$red = $red + $excesslight*($red/$alllight);
			$green = $green + $excesslight*($green/$alllight);
			$blue = $blue + $excesslight*($blue/$alllight);
		}
		if ($red > 1) {$red = 1;}
		if ($green > 1) {$green = 1;}
		if ($blue > 1) {$blue = 1;}
		if ($red < 0) {$red = 0;}
		if ($green < 0) {$green = 0;}
		if ($blue < 0) {$blue = 0;}
		
		return Color::coinit($red, $green, $blue, $special);
	}
};

class Source {
	
	public function __construct(){
    }
	
	public function getLightPosition() {return VectorClass();}
	public function getLightColor() {return Color::coinit(1, 1, 1, 0);}
	
};

class Light extends Source {
	public $position;
	public $color;

	public function __construct(){
		$position = new VectorClass();
		$color = Color::coinit(1,1,1,0);
    }

    public static function linit($p, $c){
		$position = $p;
		$color = $c;
    }	
	
	public function getLightPosition () { return $position; }
	public function getLightColor () { return $color; }
	
};

class Object {	

	public function __construct(){
    }
	
	public function getColor () { 
		return Color::coinit(0.0, 0.0, 0.0, 0); 
	}

	public function getNormalAt($intersectPos) {
		return new VectorClass();
	}
	
	public function findIntersection($ray) {
		return 0;
	}
	
};

class Sphere extends Object {
	public $center;
	public $radius;
	public $color;
	
	public function __construct(){
		$center = new VectorClass();
		$radius = 1.0;
		$color = Color::coinit(0.5,0.5,0.5, 0);
    }

    public static function spinit($centerV, $radiusV, $colorV){
		$center = $centerV;
		$radius = $radiusV;
		$color = $colorV;
    }	
	
	public function getSphereCenter () { 
		return $center; 
	}

	public function getSphereRadius () { 
		return $radius; 
	}

	public function getColor () { 
		return $color; 
	}
	
	public function getNormalAt($point) {
		$normal_Vect = $point->vectAdd($center->negative())->normalize();
		return $normal_Vect;
	}
	
	public function findIntersection($ray) {
		$sphereCen = $center;
		$sphereCen_x = $sphereCen->getVectX();
		$sphereCen_y = $sphereCen->getVectY();
		$sphereCen_z = $sphereCen->getVectZ();
		
		$rayDir = $ray->getRayDirection();
		$rayDir_x = $rayDir->getVectX();
		$rayDir_y = $rayDir->getVectY();
		$rayDir_z = $rayDir->getVectZ();

		$rayOrig = $ray->getRayOrigin();
		$rayOrig_x = $rayOrig->getVectX();
		$rayOrig_y = $rayOrig->getVectY();
		$rayOrig_z = $rayOrig->getVectZ();		
		
		$a = 1; 
		$b = (2*($rayOrig_x - $sphereCen_x)*$rayDir_x) + (2*($rayOrig_y - $sphereCen_y)*$rayDir_y) + (2*($rayOrig_z - $sphereCen_z)*$rayDir_z);
		$c = pow($rayOrig_x - $sphereCen_x, 2) + pow($rayOrig_y - $sphereCen_y, 2) + pow($rayOrig_z - $sphereCen_z, 2) - ($radius*$radius);
		
		$discr = $b*$b - 4*$c;
		
		if ($discr > 0) {

			$root1 = ((-1*$b - sqrt($discr))/2) - 0.000001;
			
			if ($root_1 > 0) {
				return $root1;
			} else {
				$root2 = ((sqrt($discr) - $b)/2) - 0.000001;
				return $root2;
			}
		} else {
			return -1;
		}
	}
	
};

class Plane extends Object {
	$normal;
	$distance;
	$color;	

	public function __construct(){
		$normal = VectorClass::vinit(1,0,0);
		$distance = 0;
		$color = Color::coinit(0.5,0.5,0.5, 0);
    }

    public static function plinit($normalValue, $distanceValue, $colorValue){
		$normal = $normalValue;
		$distance = $distanceValue;
		$color = $colorValue;
    }		
	
	public function getPlaneNormal () { 
		return $normal; 
	}
	
	public function getPlaneDistance () { 

		return $distance; 

	}
	
	public function getColor () { 
	
		return $color; 
	
	}
	
	public function getNormalAt($point) {
		return $normal;
	}
	
	public function findIntersection($ray) {
		$rayDir = $ray -> getRayDirection();
		
		$a = $rayDir->dotProduct($normal);
		
		if ($a == 0) {
			return -1;
		} else {
			$b = $normal->dotProduct($ray->getRayOrigin()->vectAdd($normal->vectMult($distance)->negative()));
			return -1*$b/$a;
		}
	}
	
};



function traceRay($intersectionHere) {
	
	$minvalin = 0;
	
	if (count($intersectionHere) == 0) {
		
		return -1;

	} else if (count($intersectionHere) == 1) {
		
		if ($intersectionHere[0] > 0) {
			
			return 0;

		} else {
		
			return -1;
		}

	} else {
		
		$max = 0;
		for ($i = 0; i < count($intersectionHere); $i++) {
			if ($max < $intersectionHere[$i]) {
				$max = $intersectionHere[$i];
			}
		}
		
		if ($max > 0) {
			
			for ($index = 0; $index < count($intersectionHere); $index++) {
				if ($intersectionHere[$index] > 0 && $intersectionHere[$index] <= $max) {
					$max = $intersectionHere[$index];
					$minvalin = $index;
				}
			}
			
			return $minvalin;

		} else if ($max < 0) {
			
			return -1;
		}
	}
}

function coloratpoint($intersectPos, $intersectRayDir, $allObjects, $intersectedAt, $light, $accuracy, $ambientlight) {
	
	$varcolor = $allObjects[$intersectedAt]->getColor();
	$varnormal = $allObjects[$intersectedAt]->getNormalAt($intersectPos);
	
	if ($varcolor->getColorSpecial() == 2) {
		// checkerboard
		
		$square = (int)floor($intersectPos->getVectX()) + (int)floor($intersectPos->getVectZ());
		
		if (($square % 2) != 0) {

			$varcolor->setColorRed(1);
			$varcolor->setColorGreen(1);
			$varcolor->setColorRed(1);			

		} else {

			$varcolor->setColorRed(0);
			$varcolor->setColorGreen(0);
			$varcolor->setColorBlue(0);

		}
	}
	
	$final_color = $varcolor->colorScalar($ambientlight);
	
	if ($varcolor->getColorSpecial() > 0 && $varcolor->getColorSpecial() <= 1) {
		
		$dot1 = $varnormal->dotProduct($intersectRayDir->negative());
		$scalar1 = $varnormal->vectMult($dot1);
		$add1 = $scalar1->vectAdd($intersectRayDir);
		$scalar2 = $add1->vectMult(2);
		$add2 = $intersectRayDir->negative()->vectAdd($scalar2);
		$refdir = $add2->normalize();
		
		$reflection_ray = Ray::rinit($intersectPos, $refdir);
		
		$eflection_intersections = array();
		
		for ($reflection_index = 0; $reflection_index < count($allObjects); $reflection_index++) {
			$refintersect[] = $allObjects[$reflection_index]->findIntersection($reflection_ray));
		}
		
		$intersectedAt_with_reflection = traceRay($refintersect);
		
		if ($intersectedAt_with_reflection != -1) {
			
			if ($refintersect[$intersectedAt_with_reflection] > $accuracy) {
				
				$refintpos = $intersectPos->vectAdd($refdir->vectMult($refintersect[$intersectedAt_with_reflection]));
				
				$reflection_intersection_rayDir = $refdir;
				
				$reflection_intersection_color = coloratpoint($refintpos, $reflection_intersection_rayDir, $allObjects, $intersectedAt_with_reflection, $light, $accuracy, $ambientlight);
				
				$final_color = $final_color->colorAdd($reflection_intersection_color->colorScalar($varcolor->getColorSpecial()));
			}
		}
	}
	
	for ($light_index = 0; $light_index < count($light); $light_index++) {
		
		$light_direction = $light[$light_index]->getLightPosition()->vectAdd($intersectPos->negative())->normalize();
		
		$cosine_angle = $varnormal->dotProduct($light_direction);
		
		if ($cosine_angle > 0) {

			$shadowed = false;
			
			$lightdist = $light[$light_index]->getLightPosition()->vectAdd($intersectPos->negative())->normalize();
			
			$lightdist_magnitude = $lightdist->magnitude();
			
			$shadow_ray = Ray::rinit($intersectPos, $light[$light_index]->getLightPosition()->vectAdd($intersectPos->negative())->normalize());
			
			$secondary_intersections = array();
			
			for ($object_index = 0; $object_index < count($allObjects) && $shadowed == false; $object_index++) {
				$secondary_intersections[$allObjects[$object_index]->findIntersection($shadow_ray)];
			}
			
			for ($c = 0; $c < count($secondary_intersections); $c++) {
				if ($secondary_intersections[$c] > $accuracy) {
					if ($secondary_intersections[$c] <= $lightdist_magnitude) {
						$shadowed = true;
					}
				}
				break;
			}
			
			if ($shadowed == false) {
				$final_color = $final_color->colorAdd($varcolor->colorMultiply($light[$light_index]->getLightColor())->colorScalar($cosine_angle));
				
				if ($varcolor->getColorSpecial() > 0 && $varcolor->getColorSpecial() <= 1) {
					// special [0-1]
					$dot1 = $varnormal->dotProduct($intersectRayDir->negative());
					$scalar1 = $varnormal->vectMult($dot1);
					$add1 = $scalar1->vectAdd($intersectRayDir);
					$scalar2 = $add1->vectMult(2);
					$add2 = $intersectRayDir->negative()->vectAdd($scalar2);
					$refdir = $add2->normalize();
					
					$specular = $refdir->dotProduct($light_direction);
					$diffuse = $varnormal->dotProduct($intersectRayDir);

					if ($specular > 0) {
						$specular = pow($specular, 10);
						$final_color = $final_color->colorAdd($light[$light_index]->getLightColor()->colorScalar($specular*$varcolor->getColorSpecial()));
						
						//$final_color = $final_color->colorAdd($light[$light_index]->getLightColor()->colorScalar(($specular+$diffuse)*varcolor->getColorSpecial()));
					}
				}
				
			}
			
		}
	}
	
	return $final_color->clip();
}
	
	$t1 = time();

	$white_light = Color::coinit(1.0, 1.0, 1.0, 0);
	$pretty_green = Color::coinit(0.5, 1.0, 0.5, 0.3);
	$maroon  = Color::coinit(0.5, 0.25, 0.25, 0);
	$tile_floor = Color::coinit(1, 1, 1, 2);
	$gray = Color::coinit(0.5, 0.5, 0.5, 0);
	$black = Color::coinit(0.0, 0.0, 0.0, 0);
	$white = Color::coinit(1.0, 1.0, 1.0, 1);
	$red = Color::coinit(1.0, 0.0, 0.0, 0);
	$green = Color::coinit(0.0, 1.0, 0.0, 0.8);
	$white_adv = Color::coinit(1.0, 1.0, 1.0, 0.8);	
	
	$dpi = 72; $width = 640; $height = 480; $n = $width*$height;
	$subdepth = 1; $subthreshold = 0.1; $aspectratio = $width/$height; $ambientlight = 0.2; $accuracy = 0.00000001;
	$O = new VectorClass(); $X = VectorClass::vinit(1,0,0); $Y = VectorClass::vinit(0,1,0); $Z = VectorClass::vinit(0,0,1);

	$pix = array();
	for ($i = 0; $i < $n; $i++) {
		$pix[$i] = new ColorClass();
	}
	
	$campos = VectorClass::vinit(1.0, 3.0, -10.0);

	$camdir = $diff_btw->negative()->normalize();
	$camright = $Y->crossProduct($camdir)->normalize();
	$camdown = $camright->crossProduct($camdir);
	$objcam = Camera::cinit($campos, $camdir, $camright, $camdown);
	
	$look_at = VectorClass::vinit(0, 1.0, 0);
	$diff_btw = VectorClass::vinit($campos->getVectX() - $look_at->getVectX(), $campos->getVectY() - $look_at->getVectY(), $campos->getVectZ() - $look_at->getVectZ());
	
	$light_position = VectorClass::vinit(-2.5,5,-7.5);
	$objlight = Light::linit($light_position, $white_light);
	$light = array();
	$light[] = $objlight;

	$new_sphere_location = Sphere::spinit(0, 1.2, 0);
	$new_sphere_location1 = Sphere::spinit(1.0, 0, 1.0);
	
	$objsphere = Sphere::spinit($new_sphere_location1, 0.8, $red);
	$objsphere2 = Sphere::spinit($new_sphere_location, 1.2, $white);

	$Yp = VectorClass::vinit(-2.5, 5, -7.5);
	$objplane = Plane::plinit($Yp, -0.05, $tile_floor);
	$allObjects = array();

	$allObjects[] = $objsphere;
	$allObjects[] = $objsphere2;
	$allObjects[] = $objplane;
	
	$thisone = 0; 
	$sub_index = 0;
	$xinter = $yinter = 0;
	$T_Red = $T_Green = $T_Blue = array();

	$finalimg = imagecreatetruecolor($width, $height);
	
	for ($x = 0; $x < $width; $x++) {
		for ($y = 0; $y < $height; $y++) {
			$thisone = $y*$width + $x;
			
			for ($subx = 0; $subx < $subdepth; $subx++) {
				for ($suby = 0; $suby < $subdepth; $suby++) {
			
					$sub_index = $suby*$subdepth + $subx;
					
					if ($subdepth != 1) {
					
						if ($width < $height) {
							
							$xinter = ($x + (double)$subx/((double)$subdepth - 1))/ $width;
							$yinter = ((($height - $y) + (double)$subx/((double)$subdepth - 1))/$height)/$aspectratio - ((($height - $width)/(double)$width)/2);

						} else if ($height < $width) {

							$xinter = (($x + (double)$subx/((double)$subdepth - 1))/$width)*$aspectratio - ((($width-$height)/(double)$height)/2);
							$yinter = (($height - y) + (double)$subx/((double)$subdepth - 1))/$height;							

						} else {
							
							$xinter = ($x + (double)$subx/((double)$subdepth - 1))/$width;
							$yinter = (($height - $y) + (double)$subx/((double)$subdepth - 1))/$height;

						}					

					} else if ($subdepth == 1) {

						if ($width < $height) {
							
							$xinter = ($x + 0.5)/ $width;
							$yinter = ((($height - $y) + 0.5)/$height)/$aspectratio - ((($height - $width)/(double)$width)/2);

						} else if ($height < $width) {
							
							$xinter = (($x+0.5)/$width)*$aspectratio - ((($width-$height)/(double)$height)/2);
							$yinter = (($height - $y) + 0.5)/$height;

						} else {
							
							$yinter = (($height - $y) + 0.5)/$height;
							$xinter = ($x + 0.5)/$width;
							

						}

					}
					
					$cryorigin = $objcam->getCameraPosition();
					$crydirection = $camdir->vectAdd($camright->vectMult($xinter - 0.5)->vectAdd($camdown->vectMult($yinter - 0.5)))->normalize();
					
					$cam_ray = Ray::rinit($cryorigin, $crydirection);
					
					$intersections = array();
					
					for ($index = 0; $index < count($allObjects); $index++) {
						$intersections[$allObjects[$index]->findIntersection($cam_ray)];
					}
					
					$intersectedAt = traceRay($intersections);
					
					if ($intersectedAt == -1) {
						
						$T_Red[$sub_index] = 0;
						$T_Green[$sub_index] = 0;
						$T_Blue[$sub_index] = 0;
					
					} else{
						
						if ($intersections[$intersectedAt] > $accuracy) {
							
							$intersectPos = $cryorigin->vectAdd($crydirection->vectMult($intersections[$intersectedAt]));
							$intersectRayDir = $crydirection;
		
							$intersection_color = coloratpoint($intersectPos, $intersectRayDir, $allObjects, $intersectedAt, $light, $accuracy, $ambientlight);
							
							$T_Red[$sub_index] = $intersection_color->getColorRed();
							$T_Green[$sub_index] = $intersection_color->getColorGreen();
							$T_Blue[$sub_index] = $intersection_color->getColorBlue();
							
							// $L = 0.27 * $T_Red[$sub_index] + 0.67 * $T_Green[$sub_index] + 0.06 * $T_Blue[$sub_index];
							// $T_Red[$sub_index] = $T_Red[$sub_index] * 0.18/$L;
							// $T_Green[$sub_index] = $T_Green[$sub_index] * 0.18/$L;
							// $T_Blue[$sub_index] = $T_Blue[$sub_index] * 0.18/$L;

							// $T_Red[$sub_index] = 10000 * ($T_Red[$sub_index] / (1 + $T_Red[$sub_index]));
							// $T_Green[$sub_index] = 10000 * ($T_Green[$sub_index] / (1 + $T_Green[$sub_index]));
							// $T_Blue[$sub_index] = 10000 * ($T_Blue[$sub_index] / (1 + $T_Blue[$sub_index]));
						}
					}
				}
			}
			
			$tot_Red = 0;
			$tot_Green = 0;
			$tot_Blue = 0;
			
			for ($ir = 0; $ir < $subdepth*$subdepth; $ir++) {
				$tot_Red = $tot_Red + $T_Red[$ir];
			}
			for ($ig = 0; $ig < $subdepth*$subdepth; $ig++) {
				$tot_Green = $tot_Green + $T_Green[$ig];
			}
			for ($ib = 0; $ib < $subdepth*$subdepth; $ib++) {
				$tot_Blue = $tot_Blue + $T_Blue[$ib];
			}
			
			$avgR = $tot_Red/($subdepth*$subdepth);
			$avgG = $tot_Green/($subdepth*$subdepth);
			$avgB = $tot_Blue/($subdepth*$subdepth);
			
			$pix[$thisone]->r = $avgR;
			$pix[$thisone]->g = $avgG;
			$pix[$thisone]->b = $avgB;

			$finapix = imagecolorallocate($finalimg, $pix[$thisone]->r, $pix[$thisone]->g, $pix[$thisone]->b);
			imagesetpixel($finalimg, $x, $y, $finapix);
		}
	}

	$t2 = time();

	echo ($t2 - $t1)/1000 . " seconds";

	header('Content-Type: image/png');
	imagepng($finalimg);
	imagedestroy($finalimg);	
	
?>
