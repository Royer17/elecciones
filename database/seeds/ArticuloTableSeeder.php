<?php

use Illuminate\Database\Seeder;
use sisVentas\Articulo;
use sisVentas\Categoria;

class ArticuloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$c1 = Categoria::create([
            'nombre' => 'Bebidas',
            'condicion' => 1,
        ]);*/
        $c1 = new Categoria;
        $c1->idcategoria = 1;
        $c1->nombre = 'Bebidas';
        $c1->slug = str_slug('Bebidas');
        $c1->condicion = 1;
        $c1->save();

        $id = 1;
        for ($i=1; $i <= 5; $i++) {
            /*Articulo::create([
                'idcategoria' => $c1->id,
                'nombre' => 'Bebida ' . $i,
                'slug' => str_slug('Bebida ' . $i),
                'price' => 5,
                'stock' => 5 + $i,
                'estado' => 'Activo',
            ]);*/
            $a = new Articulo;
            $a->idarticulo = $id;
            $a->idcategoria = 1;
            $a->nombre = 'Bebida ' . $i;
            $a->slug = str_slug('Bebida ' . $i);
            $a->price = 5;
            $a->stock = 5 + $i;
            $a->estado = 'Activo';
            $a->save();
            $id++;
        }

        /*$c2 = Categoria::create([
            'nombre' => 'Detergentes',
            'condicion' => 1,
        ]);*/
        $c2 = new Categoria;
        $c2->idcategoria = 2;
        $c2->nombre = 'Detergentes';
        $c1->slug = str_slug('Detergentes');
        $c2->condicion = 1;
        $c2->save();

        for ($i=1; $i <= 5; $i++) {
            /*Articulo::create([
                'idcategoria' => $c2->id,
                'nombre' => 'Detergente ' . $i,
                'slug' => str_slug('Detergente ' . $i),
                'price' => 5,
                'stock' => 5 + $i,
                'estado' => 'Activo',
            ]);*/
            $a = new Articulo;
            $a->idarticulo = $id;
            $a->idcategoria = 2;
            $a->nombre = 'Detergente ' . $i;
            $a->slug = str_slug('Detergente ' . $i);
            $a->price = 5;
            $a->stock = 5 + $i;
            $a->estado = 'Activo';
            $a->save();
            $id++;
        }
    }
}
