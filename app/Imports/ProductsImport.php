<?php
namespace Optica\Imports;
use Optica\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class ProductsImport implements ToModel, WithHeadingRow
{
    use Importable;
    public function model(array $row)
    {
        return new Producto([
            'idfamilia'     => $row['idfamilia'],
            'codigo'        => $row['codigo'],
            'nombre'        => $row['nombre'],
            'precio_venta'  => $row['precio_venta'],
            'descripcion'   => $row['descripcion'],
            'condicion'     => $row['condicion'],
            'idsucursal'    => $row['idsucursal']
        ]);
    }

}
