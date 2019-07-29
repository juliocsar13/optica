    @extends('main')
    @section('content')

    @if(Auth::check())
            @if(Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>

            <template v-if="menu==1">
                <familia></familia>
            </template>

            <template v-if="menu==2">
                <producto></producto>
            </template>

            <template v-if="menu==3">
                <ingreso></ingreso>
            </template>

            <template v-if="menu==4">
                <proveedor></proveedor>
            </template>

            <template v-if="menu==5">
                <venta></venta>
            </template>

            <template v-if="menu==6">
                <cliente></cliente>
            </template>

            <template v-if="menu==7">
                <user></user>
            </template>

            <template v-if="menu==8">
                <rol></rol>
            </template>

            <template v-if="menu==9">
                <consultaingreso><consultaingreso>
            </template>

            <template v-if="menu==10">
                <consultaventa><consultaventa>
            </template>

            <template v-if="menu==21">
                <consultacaja><consultacaja>
            </template>
            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>

            <template v-if="menu==13">
                <graduacion></graduacion>
            </template>

            <template v-if="menu==14">
                <caja></caja>
            </template>

            <template v-if="menu==15">
                <egresos></egresos>
            </template>

            <template v-if="menu==16">
                <cobro><cobro/>
            </template>

            <template v-if="menu==17">
                <pago></pago>
            </template>

            <template v-if="menu==18">
                <empresa></empresa>
            </template>

            <template v-if="menu==19">
                <kardex></kardex>
            </template>

            <template v-if="menu==20">
                <sucursal></sucursal>
            </template>

            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>
            <template v-if="menu==5">
                <venta></venta>
            </template>

            <template v-if="menu==6">
                <cliente></cliente>
            </template>
            <template v-if="menu==10">
                <consultaventa><consultaventa>
            </template>

            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>

            <template v-if="menu==14">
                <caja></caja>
            </template>

            <template v-if="menu==16">
                <cobro><cobro/>
            </template>
            @elseif (Auth::user()->idrol == 3)
            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>
            <template v-if="menu==1">
                <familia></familia>
            </template>

            <template v-if="menu==2">
                <producto></producto>
            </template>

            <template v-if="menu==3">
                <ingreso></ingreso>
            </template>

            <template v-if="menu==4">
                <proveedor></proveedor>
            </template>
            <template v-if="menu==9">
                <consultaingreso><consultaingreso>
            </template>
            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>
            @else

            @endif

    @endif
@endsection
