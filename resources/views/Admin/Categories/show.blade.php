<div class="row ">
    <div class="col-lg-8 order-last">
        <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="fas fa-burn text-dark fs-4"></i>
                <h6 class="fs-4  mb-0">
                    {{ $category->name }}
                </h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="fas fa-burn text-dark fs-4"></i>
                <h6 class="fs-4  mb-0">
                    {{ $category->slug }}
                </h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="fas fa-indent text-dark fs-4"></i>
                <h6 class="fs-4 mb-0">
                    Index : &nbsp;
                    {{ $category->index }}
                </h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="fas fa-info-circle text-dark fs-4"></i>
                <h6 class="fs-4  mb-0">
                    Description :
                </h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-4">
                <h6 class="fs-3  mb-0">
                    {!! $category->description !!}
                </h6>
            </li>
        </ul>
    </div>
</div>
