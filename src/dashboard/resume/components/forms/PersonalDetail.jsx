import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { ResumeInfoContext } from '@/context/ResumeInfoContext'
import { LoaderCircle } from 'lucide-react'
import React, { useContext, useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'
import GlobalApi from './../../../../../service/GlobalApi'
import { toast } from 'sonner'

function PersonalDetail({ enabledNext }) {
    const params = useParams()
    const { resumeInfo, setResumeInfo } = useContext(ResumeInfoContext)

    const [formData, setFormData] = useState({})
    const [loading, setLoading] = useState(false)

    // Disable "Next" on initial mount
    useEffect(() => {
        enabledNext(false)
        if (resumeInfo) {
            setFormData(resumeInfo)
        }
        console.log("Resume Info:", resumeInfo)
    }, [resumeInfo, enabledNext])

    const handleInputChange = (e) => {
        const { name, value } = e.target
        setFormData((prevFormData) => ({
            ...prevFormData,
            [name]: value
        }))
        setResumeInfo((prevResumeInfo) => ({
            ...prevResumeInfo,
            [name]: value
        }))
    }

    // const onSave = (e) => {
    //     e.preventDefault()
    //     setLoading(true)
    //     if (!resumeInfo?.resumeId) {
    //         console.error("resumeId is not available in resumeInfo.");
    //         toast("Failed to update details");
    //         setLoading(false);
    //         return;
    //     }
    //     const data = { data: formData }

    //     GlobalApi.UpdateResumeDetail(resumeInfo.documentId, data).then(
    //         (resp) => {
    //             console.log(resp);
    //             enabledNext(true);
    //             setLoading(false);
    //             toast("Details updated");
    //         },
    //         (error) => {
    //             console.error("Save error:", error.response ? error.response.data : error.message);

    //             // console.error("Save error:", error.response || error.message);
    //             toast("Failed to update details");
    //             setLoading(false);
    //         }
    //     );



    // }

    const onSave = (e) => {
        e.preventDefault();
        setLoading(true);
        const payload = {
            data: {  // Wrap your fields inside "data"
                title: formData.title,
                firstName: formData.firstName,
                lastName: formData.lastName,
                address: formData.address,
                phone: formData.phone,
                email: formData.email,
                jobTitle: formData.jobTitle
            }
        };
        GlobalApi.UpdateResumeDetail(resumeInfo.documentId, payload)
            .then(resp => {
                console.log(resp);
                enabledNext(true);
                setLoading(false);
                toast("Details updated");
            })
            .catch((error) => {
                console.error("Save error:", error);
                setLoading(false);
            });
    };

    return (
        <div className='p-5 shadow-lg rounded-lg border-t-primary border-t-4 mt-10'>
            <h2 className='font-bold text-lg'>Personal Detail</h2>
            <p>Get Started with the basic information</p>

            <form onSubmit={onSave}>
                <div className='grid grid-cols-2 mt-5 gap-3'>
                    <div>
                        <label className='text-sm'>First Name</label>
                        <Input
                            name="firstName"
                            value={formData.firstName || ""}
                            required
                            onChange={handleInputChange}
                        />
                    </div>
                    <div>
                        <label className='text-sm'>Last Name</label>
                        <Input
                            name="lastName"
                            value={formData.lastName || ""}
                            required
                            onChange={handleInputChange}
                        />
                    </div>
                    <div className='col-span-2'>
                        <label className='text-sm'>Job Title</label>
                        <Input
                            name="jobTitle"
                            value={formData.jobTitle || ""}
                            required
                            onChange={handleInputChange}
                        />
                    </div>
                    <div className='col-span-2'>
                        <label className='text-sm'>Address</label>
                        <Input
                            name="address"
                            value={formData.address || ""}
                            required
                            onChange={handleInputChange}
                        />
                    </div>
                    <div>
                        <label className='text-sm'>Phone</label>
                        <Input
                            name="phone"
                            value={formData.phone || ""}
                            required
                            onChange={handleInputChange}
                        />
                    </div>
                    <div>
                        <label className='text-sm'>Email</label>
                        <Input
                            name="email"
                            value={formData.email || ""}
                            required
                            onChange={handleInputChange}
                        />
                    </div>
                </div>
                <div className='mt-3 flex justify-end'>
                    <Button type="submit" disabled={loading}>
                        {loading ? <LoaderCircle className='animate-spin' /> : 'Save'}
                    </Button>
                </div>
            </form>
        </div>
    )
}

export default PersonalDetail
