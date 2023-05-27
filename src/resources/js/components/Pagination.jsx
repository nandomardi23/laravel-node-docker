import React from 'react';
import { Link } from '@inertiajs/inertia-react';

export default function Pagination({ links }) {

    function getClassName(active) {
        if (active) {
            return "mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary bg-blue-700 text-white";
        } else {
            return "mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary";
        }
    }

    return (
//         <nav aria-label="...">
//   <ul class="pagination">
//     <li class="page-item disabled">
//       <a class="page-link">Previous</a>
//     </li>
//     <li class="page-item"><a class="page-link" href="#">1</a></li>
//     <li class="page-item active" aria-current="page">
//       <a class="page-link" href="#">2</a>
//     </li>
//     <li class="page-item"><a class="page-link" href="#">3</a></li>
//     <li class="page-item">
//       <a class="page-link" href="#">Next</a>
//     </li>
//   </ul>
// </nav>

        links.length > 3 && (
            <div className="mb-4">
                <div className="flex flex-wrap mt-8">
                    {/* {links.map((link, key) => (
                        link.url === null ?
                            (<div
                                className="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                            >{link.label}</div>) :

                            (<Link
                                className={getClassName(link.active)}
                                href={link.url}
                            >{link.label}</Link>)
                    ))} */}
                </div>
            </div>
        )
    );
}

Pagination.layout = page => <Layout children={page} title="Tes title order" />

