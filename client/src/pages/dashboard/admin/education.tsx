import React from "react";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { getActiveMenuItem } from "@/config/admin_menu";
import EducationManagement from "@/components/content/EducationManagement";

function EducationManagementPage() {
  const menuItems = getActiveMenuItem('/dashboard/admin/education');

  return (
    <DashboardLayout
      title="Education Management"
      userRole="admin"
      menuItems={menuItems}
    >
      <EducationManagement />
    </DashboardLayout>
  );
}

export default EducationManagementPage;
